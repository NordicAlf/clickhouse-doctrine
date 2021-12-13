<?php
declare(strict_types=1);

namespace ClickhouseDoctrine\Types;

interface DatableClickHouseType extends ClickHouseType
{
    public const TYPE_DATE      = 'Date';
    public const TYPE_DATE_TIME = 'DateTime';
}
