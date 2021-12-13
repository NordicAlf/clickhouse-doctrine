<?php
declare(strict_types=1);

namespace ClickhouseDoctrine\Types;

use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use function array_map;
use function implode;

/**
 * Array(String) Type class
 */
class ArrayStringType extends ArrayType implements StringClickHouseType
{
    public function getBaseClickHouseType() : string
    {
        return StringClickHouseType::TYPE_STRING;
    }

    /**
     * {@inheritDoc}
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return '[' . implode(
            ', ',
            array_map(
                function (string $value) use ($platform) {
                        return $platform->quoteStringLiteral($value);
                },
                (array) $value
            )
        ) . ']';
    }

    /**
     * {@inheritDoc}
     */
    public function getBindingType() : int
    {
        return ParameterType::INTEGER;
    }
}
