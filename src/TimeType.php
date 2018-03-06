<?php
declare(strict_types=1);

namespace Pauci\DateTime\Doctrine;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Pauci\DateTime\DateTime;
use Pauci\DateTime\DateTimeInterface;

class TimeType extends \Doctrine\DBAL\Types\TimeType
{
    public function convertToPHPValue($value, AbstractPlatform $platform): ?DateTimeInterface
    {
        if ($value === null || $value instanceof DateTimeInterface) {
            return $value;
        }

        $val = DateTime::createFromFormat('!' . $platform->getTimeFormatString(), $value);

        if (!$val) {
            throw ConversionException::conversionFailedFormat(
                $value,
                $this->getName(),
                $platform->getTimeFormatString()
            );
        }

        return $val;
    }
}
