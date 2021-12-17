<?php
declare(strict_types=1);

namespace ClickhouseDoctrine;

use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Connection as DoctrineConnection;
use function strtoupper, substr, trim;
use Closure;

/**
 * ClickHouse Connection
 */
class Connection extends DoctrineConnection
{
    /**
     * {@inheritDoc}
     */
    public function executeUpdate(string $sql, array $params = [], array $types = []): int
    {
        // ClickHouse has no UPDATE or DELETE statements
        $command = strtoupper(substr(trim($sql), 0, 6));

        if ($command === 'UPDATE' || $command === 'DELETE') {
            throw new ClickHouseException('UPDATE and DELETE are not allowed in ClickHouse');
        }

        return parent::executeStatement($sql, $params, $types);
    }

    /**
     * @throws Exception
     */
    public function delete(mixed $tableExpression, array $identifier, array $types = []): void
    {
        throw Exception::notSupported(__METHOD__);
    }

    /**
     * @throws Exception
     */
    public function update(mixed $tableExpression, array $data, array $identifier, array $types = []): void
    {
        throw Exception::notSupported(__METHOD__);
    }

    /**
     * all methods below throw exceptions, because ClickHouse has not transactions
     */

    /**
     * @throws Exception
     */
    public function setTransactionIsolation(mixed $level): void
    {
        throw Exception::notSupported(__METHOD__);
    }

    /**
     * @throws Exception
     */
    public function getTransactionIsolation(): void
    {
        throw Exception::notSupported(__METHOD__);
    }

    /**
     * @throws Exception
     */
    public function getTransactionNestingLevel(): void
    {
        throw Exception::notSupported(__METHOD__);
    }

    /**
     * @throws Exception
     */
    public function transactional(Closure $func): void
    {
        throw Exception::notSupported(__METHOD__);
    }

    /**
     * @throws Exception
     */
    public function setNestTransactionsWithSavepoints(mixed $nestTransactionsWithSavepoints): void
    {
        throw Exception::notSupported(__METHOD__);
    }

    /**
     * @throws Exception
     */
    public function getNestTransactionsWithSavepoints(): void
    {
        throw Exception::notSupported(__METHOD__);
    }

    /**
     * @throws Exception
     */
    public function beginTransaction(): void
    {
        throw Exception::notSupported(__METHOD__);
    }

    /**
     * @throws Exception
     */
    public function commit(): void
    {
        throw Exception::notSupported(__METHOD__);
    }

    /**
     * @throws Exception
     */
    public function rollBack(): void
    {
        throw Exception::notSupported(__METHOD__);
    }

    /**
     * @throws Exception
     */
    public function createSavepoint(mixed $savepoint): void
    {
        throw Exception::notSupported(__METHOD__);
    }

    /**
     * @throws Exception
     */
    public function releaseSavepoint(mixed $savepoint): void
    {
        throw Exception::notSupported(__METHOD__);
    }

    /**
     * @throws Exception
     */
    public function rollbackSavepoint(mixed $savepoint): void
    {
        throw Exception::notSupported(__METHOD__);
    }

    /**
     * @throws Exception
     */
    public function setRollbackOnly(): void
    {
        throw Exception::notSupported(__METHOD__);
    }

    /**
     * @throws Exception
     */
    public function isRollbackOnly(): void
    {
        throw Exception::notSupported(__METHOD__);
    }
}
