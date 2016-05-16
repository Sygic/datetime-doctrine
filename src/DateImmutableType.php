<?php

namespace Pauci\DateTime\Doctrine;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Pauci\DateTime\DateTimeImmutable;

class DateImmutableType extends \Doctrine\DBAL\Types\DateType
{
    const NAME = 'date_immutable';

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if ($value === null || $value instanceof DateTimeImmutable) {
            return $value;
        }

        $val = DateTimeImmutable::createFromFormat('!' . $platform->getDateFormatString(), $value);
        if (!$val) {
            throw ConversionException::conversionFailedFormat(
                $value,
                $this->getName(),
                $platform->getDateFormatString()
            );
        }

        return $val;
    }
}
