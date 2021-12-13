<?php
declare(strict_types=1);

namespace ClickhouseDoctrine\Types;

/**
 * Array(Float32) Type
 */
class ArrayFloat32Type extends ArrayType implements BitNumericalClickHouseType
{
    public function getBits() : int
    {
        return BitNumericalClickHouseType::THIRTY_TWO_BIT;
    }

    public function getBaseClickHouseType() : string
    {
        return NumericalClickHouseType::TYPE_FLOAT;
    }
}
