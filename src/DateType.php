<?php
declare(strict_types=1);

namespace Pauci\DateTime\Doctrine;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Pauci\DateTime\DateTime;
use Pauci\DateTime\DateTimeInterface;
use Pauci\DateTime\Exception\InvalidTimeStringException;

class DateType extends \Doctrine\DBAL\Types\DateType
{
    public function convertToPHPValue($value, AbstractPlatform $platform): ?DateTimeInterface
    {
        if ($value === null || $value instanceof DateTimeInterface) {
            return $value;
        }

        try {
            return DateTime::createFromFormat('!' . $platform->getDateFormatString(), $value);
        } catch (InvalidTimeStringException $e) {
            throw ConversionException::conversionFailedFormat(
                $value,
                $this->getName(),
                $platform->getDateFormatString(),
                $e
            );
        }
    }
}
