<?php
declare(strict_types=1);

namespace ClickhouseDoctrine\Types;

/**
 * Array(UInt16) Type
 */
class ArrayUInt16Type extends ArrayType implements BitNumericalClickHouseType, UnsignedNumericalClickHouseType
{
    public function getBits() : int
    {
        return BitNumericalClickHouseType::SIXTEEN_BIT;
    }

    public function getBaseClickHouseType() : string
    {
        return NumericalClickHouseType::TYPE_INT;
    }
}
