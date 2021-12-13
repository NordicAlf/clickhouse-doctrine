<?php
declare(strict_types=1);

namespace ClickhouseDoctrine\Types;

/**
 * Array(UInt32) Type
 */
class ArrayUInt32Type extends ArrayType implements BitNumericalClickHouseType, UnsignedNumericalClickHouseType
{
    public function getBits() : int
    {
        return BitNumericalClickHouseType::THIRTY_TWO_BIT;
    }

    public function getBaseClickHouseType() : string
    {
        return NumericalClickHouseType::TYPE_INT;
    }
}
