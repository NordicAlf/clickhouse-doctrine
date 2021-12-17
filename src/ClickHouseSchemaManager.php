<?php
declare(strict_types=1);

namespace ClickhouseDoctrine;

use Doctrine\DBAL\Schema\{AbstractSchemaManager, Column, Index, View};
use Doctrine\DBAL\Types\Type;
use const CASE_LOWER;
use function array_change_key_case, array_filter, array_key_exists, array_map, array_reverse, current, explode, is_array;
use function preg_match, preg_replace, str_replace, stripos, strpos, strtolower, trim;

/**
 * Schema manager for the ClickHouse DBMS.
 */
class ClickHouseSchemaManager extends AbstractSchemaManager
{
    /**
     * {@inheritdoc}
     */
    protected function _getPortableTableDefinition(mixed $table): string
    {
        return $table['name'];
    }

    /**
     * {@inheritdoc}
     */
    protected function _getPortableViewDefinition(mixed $view): View
    {
        $statement = $this->_conn->fetchOne('SHOW CREATE TABLE ' . $view['name']);

        return new View($view['name'], $statement);
    }

    /**
     * {@inheritdoc}
     */
    public function listTableIndexes(mixed $table): array
    {
        $tableView = $this->_getPortableViewDefinition(['name' => $table]);

        preg_match(
            '/MergeTree\(([\w+, \(\)]+)(?= \(((?:[^()]|\((?2)\))+)\),)/mi',
            $tableView->getSql(),
            $matches
        );

        if (is_array($matches) && array_key_exists(2, $matches)) {
            $indexColumns = array_filter(
                array_map('trim', explode(',', $matches[2])),
                function (string $column) {
                    return false === strpos($column, '(');
                }
            );

            return [
                new Index(
                    current(array_reverse(explode('.', $table))) . '__pk',
                    $indexColumns,
                    false,
                    true
                ),
            ];
        }

        return [];
    }

    /**
     * {@inheritdoc}
     */
    protected function _getPortableTableColumnDefinition(mixed $tableColumn): Column
    {
        $tableColumn = array_change_key_case($tableColumn, CASE_LOWER);

        $dbType  = $columnType = trim($tableColumn['type']);
        $length  = null;
        $fixed   = false;
        $notnull = true;

        if (preg_match('/(Nullable\((\w+)\))/i', $columnType, $matches)) {
            $columnType = str_replace($matches[1], $matches[2], $columnType);
            $notnull    = false;
        }

        if (stripos($columnType, 'fixedstring') === 0) {
            // get length from FixedString definition
            $length = preg_replace('~.*\(([0-9]*)\).*~', '$1', $columnType);
            $dbType = 'fixedstring';
            $fixed  = true;
        }

        $unsigned = false;
        if (stripos($columnType, 'uint') === 0) {
            $unsigned = true;
        }

        if (! isset($tableColumn['name'])) {
            $tableColumn['name'] = '';
        }

        $default = null;
        //TODO process not only DEFAULT type, but ALIAS and MATERIALIZED too
        if ($tableColumn['default_expression'] && strtolower($tableColumn['default_type']) === 'default') {
            $default = $tableColumn['default_expression'];
        }

        $options = [
            'length' => $length,
            'notnull' => $notnull,
            'default' => $default,
            'primary' => false,
            'fixed' => $fixed,
            'unsigned' => $unsigned,
            'autoincrement' => false,
            'comment' => null,
        ];

        return new Column(
            $tableColumn['name'],
            Type::getType($this->_platform->getDoctrineTypeMapping($dbType)),
            $options
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function _getPortableDatabaseDefinition(mixed $database): string
    {
        return $database['name'];
    }
}
