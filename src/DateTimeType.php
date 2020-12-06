<?php
declare(strict_types=1);

namespace Pauci\DateTime\Doctrine;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Pauci\DateTime\DateTime;
use Pauci\DateTime\DateTimeInterface;
use Pauci\DateTime\Exception\InvalidTimeStringException;

class DateTimeType extends \Doctrine\DBAL\Types\DateTimeType
{
    public function convertToPHPValue($value, AbstractPlatform $platform): ?DateTimeInterface
    {
        if ($value === null || $value instanceof DateTimeInterface) {
            return $value;
        }

        try {
            return DateTime::fromFormat($platform->getDateTimeFormatString(), $value);
        } catch (InvalidTimeStringException $e) {
            $val = date_create($value);
            if ($val) {
                return DateTime::fromDateTime($val);
            }

            throw ConversionException::conversionFailedFormat(
                $value,
                $this->getName(),
                $platform->getDateTimeFormatString(),
                $e
            );
        }
    }
}
