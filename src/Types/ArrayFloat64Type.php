<?php
declare(strict_types=1);

namespace ClickhouseDoctrine\Types;

/**
 * Array(Float64) Type
 */
class ArrayFloat64Type extends ArrayType implements BitNumericalClickHouseType
{
    public function getBits() : int
    {
        return BitNumericalClickHouseType::SIXTY_FOUR_BIT;
    }

    public function getBaseClickHouseType() : string
    {
        return NumericalClickHouseType::TYPE_FLOAT;
    }
}
