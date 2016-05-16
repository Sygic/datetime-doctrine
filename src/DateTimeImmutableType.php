<?php

namespace Pauci\DateTime\Doctrine;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Pauci\DateTime\DateTimeImmutable;

class DateTimeImmutableType extends \Doctrine\DBAL\Types\DateTimeType
{
    const NAME = 'datetime_immutable';

    public function getName()
    {
        return self::NAME;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if ($value === null || $value instanceof DateTimeImmutable) {
            return $value;
        }

        $val = DateTimeImmutable::fromFormat($platform->getDateTimeFormatString(), $value);

        if (!$val) {
            $val = date_create($value);
            if ($val) {
                $val = DateTimeImmutable::fromDateTime($val);
            }
        }

        if (!$val) {
            throw ConversionException::conversionFailedFormat(
                $value,
                $this->getName(),
                $platform->getDateTimeFormatString()
            );
        }

        return $val;
    }
}
