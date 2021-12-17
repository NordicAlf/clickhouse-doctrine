<?php
declare(strict_types=1);

namespace ClickhouseDoctrine;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver\Connection as DriverConnection;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use ClickhouseDoctrine\Exception\ExceptionConverter;
use Doctrine\DBAL\Result;
use Doctrine\DBAL\Schema\AbstractSchemaManager;
use Doctrine\DBAL\Driver as DoctrineDriver;

/**
 * ClickHouse Driver
 */
class Driver implements DoctrineDriver
{
    /**
     * {@inheritDoc}
     */
    public function connect(array $params, $user = null, $password = null, array $driverOptions = []): DriverConnection
    {
        if ($user === null) {
            if (! isset($params['user'])) {
                throw new ClickHouseException('Connection parameter `user` is required');
            }

            $user = $params['user'];
        }

        if ($password === null) {
            if (! isset($params['password'])) {
                throw new ClickHouseException('Connection parameter `password` is required');
            }

            $password = $params['password'];
        }

        if (! isset($params['host'])) {
            throw new ClickHouseException('Connection parameter `host` is required');
        }

        if (! isset($params['port'])) {
            throw new ClickHouseException('Connection parameter `port` is required');
        }

        if (! isset($params['dbname'])) {
            throw new ClickHouseException('Connection parameter `dbname` is required');
        }

        return new ClickHouseConnection($params, (string) $user, (string) $password, $this->getDatabasePlatform());
    }

    /**
     * {@inheritDoc}
     */
    public function getDatabasePlatform(): AbstractPlatform
    {
        return new ClickHousePlatform();
    }

    /**
     * {@inheritDoc}
     */
    public function getSchemaManager(Connection $conn, AbstractPlatform $platform): AbstractSchemaManager
    {
        return new ClickHouseSchemaManager($conn, $platform);
    }

    /**
     * {@inheritDoc}
     */
    public function getName(): string
    {
        return 'clickhouse';
    }

    /**
     * {@inheritDoc}
     */
    public function getDatabase(Connection $conn): Result
    {
        if (isset($conn->getParams()['dbname'])) {
            return $conn->getParams()['dbname'];
        } else {
            return $conn->prepare('SELECT currentDatabase() as dbname')->executeQuery();
        }
    }

    public function getExceptionConverter(): ExceptionConverter
    {
        return new ExceptionConverter();
    }
}
