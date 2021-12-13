<?php
declare(strict_types=1);

namespace ClickhouseDoctrine\Types;

use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\Platforms\AbstractPlatform;

/**
 * BigInt Type
 */
class BigIntType extends \Doctrine\DBAL\Types\BigIntType
{
    /**
     * {@inheritdoc}
     */
    public function getBindingType() : int
    {
        return ParameterType::INTEGER;
    }

    /**
     * {@inheritdoc}
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return (int) $value;
    }

    /**
     * {@inheritdoc}
     */
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform) : string
    {
        return (empty($fieldDeclaration['unsigned']) ? '' : 'U') . 'Int64';
    }
}
