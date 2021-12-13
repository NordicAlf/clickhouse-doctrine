<?php
declare(strict_types=1);

namespace ClickhouseDoctrine\Types;

/**
 * Array(UInt64) Type
 */
class ArrayUInt64Type extends ArrayType implements BitNumericalClickHouseType, UnsignedNumericalClickHouseType
{
    public function getBits() : int
    {
        return BitNumericalClickHouseType::SIXTY_FOUR_BIT;
    }

    public function getBaseClickHouseType() : string
    {
        return NumericalClickHouseType::TYPE_INT;
    }
}
