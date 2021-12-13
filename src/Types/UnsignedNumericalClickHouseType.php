<?php
declare(strict_types=1);

namespace ClickhouseDoctrine\Types;

interface UnsignedNumericalClickHouseType extends NumericalClickHouseType
{
    public const UNSIGNED_CHAR = 'U';
}
