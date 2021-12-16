# Doctrine DBAL ClickHouse Driver with fixes and updates

## Installation

```
composer require nordicalf/clickhouse-doctrine
```

## Initialization
```php
# .env
###< clickhouse ###>
CLICKHOUSE_IP=127.0.0.1
CLICKHOUSE_DB=your_db
CLICKHOUSE_USER=default
CLICKHOUSE_PASSWORD=your_password
CLICKHOUSE_PORT=8123
CLICKHOUSE_CONSOLE_PORT=9000
```

### Symfony
configure...
```yml
# app/config/packages/doctrine.yml
doctrine:
  dbal:
    default_connection: default
    connections:
      default:
        url: '%env(resolve:DATABASE_URL)%'
      clickhouse:
        host: '%env(resolve:CLICKHOUSE_IP)%'
        port: '%env(resolve:CLICKHOUSE_PORT)%'
        user: '%env(resolve:CLICKHOUSE_USER)%'
        password: '%env(resolve:CLICKHOUSE_PASSWORD)%'
        dbname: '%env(resolve:CLICKHOUSE_DB)%'
        driver_class: ClickhouseDoctrine\Driver
        wrapper_class: ClickhouseDoctrine\Connection
        options:
          enable_http_compression: 1
          max_execution_time: 60
```
...and get from the service container
```php
$conn = $this->get('doctrine.dbal.clickhouse_connection');
```
