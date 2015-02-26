<?php

/**
 * Knlv\Zf2\Hydrator\Strategy\DateTimeStrategy
 *
 * @link https://github.com/kanellov/zf2-authentication
 * @copyright Copyright (c) 2015 Vassilis Kanellopoulos - contact@kanellov.com
 * @license https://raw.githubusercontent.com/kanellov/zf2-authentication/master/LICENSE
 */

namespace Knlv\Zf2\Hydrator\Strategy;

use DateTime;
use DateTimeZone;
use Zend\Stdlib\Hydrator\Strategy\StrategyInterface;

class DateTimeStrategy implements StrategyInterface
{
    const DEFAULT_FORMAT = 'Y-m-d H:i:s';
    /**
     * @var string
     */
    protected $format;

    /**
     * @var DateTimeZone
     */
    protected $timezone;

    /**
     * Class constructor
     *
     * @param string $format
     * @param string|DateTimeZone $timezone
     */
    public function __construct($format = null, $timezone = null)
    {
        if (!is_null($format)) {
            $this->setFormat($format);
        }

        if (!is_null($timezone)) {
            $this->setTimezone($timezone);
        }
    }

    /**
     *
     * @param mixed $value
     * @return mixed
     */
    public function extract($value)
    {
        if (!$value instanceof DateTime) {
            return $value;
        }
        $value->setTimezone($this->getTimezone());
        return $value->format($this->getFormat());
    }

    /**
     *
     * @param mixed $value
     * @return mixed
     */
    public function hydrate($value)
    {
        if (is_string($value) && empty($value)) {
            return null;
        }
        
        if (is_string($value)) {
            return DateTime::createFromFormat(
                $this->getFormat(),
                $value,
                $this->getTimezone()
            );
        }

        return $value;
    }

    /**
     * Gets the value of format.
     *
     * @return string
     */
    public function getFormat()
    {
        if (is_null($this->format)) {
            $this->setFormat(self::DEFAULT_FORMAT);
        }

        return $this->format;
    }

    /**
     * Sets the value of format.
     *
     * @param string $format the format
     *
     * @return self
     */
    public function setFormat($format)
    {
        $this->format = (string) $format;

        return $this;
    }

    /**
     * Gets the value of timezone.
     *
     * @return DateTimeZone
     */
    public function getTimezone()
    {
        if (is_null($this->timezone)) {
            $this->setTimezone(date_default_timezone_get());
        }

        return $this->timezone;
    }

    /**
     * Sets the value of timezone.
     *
     * @param string|DateTimeZone $timezone the timezone
     *
     * @return self
     */
    public function setTimezone($timezone)
    {
        if (is_string($timezone)) {
            $timezone = new DateTimeZone($timezone);
        }
        $this->timezone = $timezone;

        return $this;
    }
}
