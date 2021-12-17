<?php
declare(strict_types=1);

namespace ClickhouseDoctrine;

use ClickHouseDB\Client as Smi2CHClient;
use ClickHouseDB\Exception\TransportException;
use Doctrine\DBAL\Driver\Connection;
use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Driver\Statement;
use Doctrine\DBAL\Driver\Result;
use function array_merge;
use function func_get_args;

/**
 * ClickHouse implementation for the Connection interface.
 */
class ClickHouseConnection implements Connection
{
    protected Smi2CHClient $smi2CHClient;
    protected AbstractPlatform $platform;

    /**
     * Connection constructor
     *
     * @param mixed[] $params
     */
    public function __construct(array $params, string $username, string $password, AbstractPlatform $platform)
    {
        $this->smi2CHClient = new Smi2CHClient([
            'host' => $params['host'] ?? 'localhost',
            'port' => $params['port'] ?? 8123,
            'username' => $username,
            'password' => $password,
        ], array_merge([
            'database' => $params['dbname'] ?? 'default',
        ], $params['driverOptions'] ?? []));
        $this->platform = $platform;
    }

    /**
     * {@inheritDoc}
     */
    public function prepare(string $sql): Statement
    {
        return new ClickHouseStatement($this->smi2CHClient, $sql, $this->platform);
    }

    /**
     * {@inheritDoc}
     */
    public function query(string $sql): Result
    {
        $stmt = $this->prepare($sql);

        return $stmt->execute();
    }

    /**
     * {@inheritDoc}
     */
    public function quote(mixed $value, $type = ParameterType::STRING)
    {
        if ($type === ParameterType::INTEGER) {
            return $value;
        }

        return $this->platform->quoteStringLiteral($value);
    }

    /**
     * {@inheritDoc}
     */
    public function exec(string $sql): int
    {
        $stmt = $this->prepare($sql);
        $stmt->execute();

        return $stmt->rowCount();
    }

    /**
     * {@inheritDoc}
     */
    public function lastInsertId($name = null): void
    {
        throw new \LogicException('Unable to get last insert id in ClickHouse');
    }

    /**
     * {@inheritDoc}
     */
    public function beginTransaction(): void
    {
        throw new \LogicException('Transactions are not allowed in ClickHouse');
    }

    /**
     * {@inheritDoc}
     */
    public function commit(): void
    {
        throw new \LogicException('Transactions are not allowed in ClickHouse');
    }

    /**
     * {@inheritDoc}
     */
    public function rollBack(): void
    {
        throw new \LogicException('Transactions are not allowed in ClickHouse');
    }

    /**
     * {@inheritDoc}
     */
    public function errorCode(): void
    {
        throw new \LogicException('You need to implement ClickHouseConnection::errorCode()');
    }

    /**
     * {@inheritDoc}
     */
    public function errorInfo(): void
    {
        throw new \LogicException('You need to implement ClickHouseConnection::errorInfo()');
    }

    /**
     * {@inheritDoc}
     */
    public function ping(): bool
    {
        return $this->smi2CHClient->ping();
    }

    /**
     * {@inheritDoc}
     */
    public function getServerVersion(): string
    {
        try {
            return $this->smi2CHClient->getServerVersion();
        } catch (TransportException $exception) {
            return '';
        }
    }

    /**
     * {@inheritDoc}
     */
    public function requiresQueryForServerVersion(): bool
    {
        return true;
    }
}
