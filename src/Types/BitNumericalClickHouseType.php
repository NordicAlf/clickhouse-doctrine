<?php
declare(strict_types=1);

namespace ClickhouseDoctrine\Types;

interface BitNumericalClickHouseType extends NumericalClickHouseType
{
    public const EIGHT_BIT      = 8;
    public const SIXTEEN_BIT    = 16;
    public const THIRTY_TWO_BIT = 32;
    public const SIXTY_FOUR_BIT = 64;

    public function getBits() : int;
}
