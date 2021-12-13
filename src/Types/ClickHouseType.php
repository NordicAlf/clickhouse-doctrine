<?php
declare(strict_types=1);

namespace ClickhouseDoctrine\Types;

interface ClickHouseType
{
    public function getBaseClickHouseType() : string;
}
