<?php

namespace WillCorp\ZombieBundle\Game\Helper;

/**
 * Class Date
 *
 * @author Yann Eugoné <yann.eugone@gmail.com>
 */
class Date
{
    const FORMAT_SECONDS = 1;
    const FORMAT_MINUTES = 60;
    const FORMAT_HOURS = 3600;
    const FORMAT_DAYS = 86400;

    /**
     * This class should not be instantiated
     */
    private function __construct()
    {
    }

    /**
     * Return the list of the formats
     *
     * @return array
     */
    public static function getAvailableFormats()
    {
        return array(
            static::FORMAT_SECONDS,
            static::FORMAT_MINUTES,
            static::FORMAT_HOURS,
            static::FORMAT_DAYS,
        );
    }

    /**
     * Get the difference between now and the given $date in the given $format
     *          {@see WillCorp\ZombieBundle\Game\Helper\Date::getAvailableFormats()}
     *
     * @param \DateTime $date   The reference date
     * @param integer   $format The format
     *
     * @return integer
     * @throws \Exception If the provided format is invalid
     */
    public static function getElapsedTime(\DateTime $date, $format = self::FORMAT_MINUTES)
    {
        if (!in_array($format, static::getAvailableFormats())) {
            throw new \Exception(sprintf(
                'You must provide a valid format : "%s"',
                implode('", "', static::getAvailableFormats())
            ));
        }

        $now = static::now();

        return intval(floor(abs($now->getTimestamp() - $date->getTimestamp()) / $format));
    }

    /**
     * Internal used for tests
     *
     * @return \DateTime
     */
    protected static function now()
    {
        return new \DateTime();
    }
}