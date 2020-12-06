<?php
declare(strict_types=1);

namespace Pauci\DateTime\Doctrine;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Pauci\DateTime\DateInterval;

class DateIntervalType extends Type
{
    public const NAME = 'date_interval';

    public function getName(): string
    {
        return self::NAME;
    }

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getVarcharTypeDeclarationSQL($column);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        return $value !== null ? (string) $value : null;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?DateInterval
    {
        if (null === $value || $value instanceof DateInterval) {
            return $value;
        }

        return DateInterval::fromString($value);
    }
}
