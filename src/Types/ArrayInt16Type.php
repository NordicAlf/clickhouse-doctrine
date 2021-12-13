<?php
declare(strict_types=1);

namespace ClickhouseDoctrine\Types;

/**
 * Array(Int16) Type
 */
class ArrayInt16Type extends ArrayType implements BitNumericalClickHouseType
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
