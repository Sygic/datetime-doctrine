<?php

namespace Pauci\DateTime\Doctrine;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Pauci\DateTime\DateTime;

class DateTimeType extends \Doctrine\DBAL\Types\DateTimeType
{
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if ($value === null || $value instanceof DateTime) {
            return $value;
        }

        $val = DateTime::fromFormat($platform->getDateTimeFormatString(), $value);

        if (!$val) {
            $val = date_create($value);
            if ($val) {
                $val = DateTime::fromDateTime($val);
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
