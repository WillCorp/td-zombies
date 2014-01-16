<?php
/**
 * This file is part of the WillCorpZombieBundle package
 *
 * (c) Yann Eugoné <yann.eugone@gmail.com> ; William Sauvan <william.sauvan@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace WillCorp\ZombieBundle\Tests\Game\Helper;

use WillCorp\ZombieBundle\Game\Helper\Date;

/**
 * Test the game "date" helper class
 *          {@link WillCorp\ZombieBundle\Game\Helper\Date}
 *
 * @author Yann Eugoné <yann.eugone@gmail.com>
 */
class DateTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test the "getElapsedTime" method exception
     * When a wrong format is given
     *
     * @expectedException \Exception
     * @expectedExceptionMessage You must provide a valid format : "1", "60", "3600", "86400"
     */
    public function testElapsedTimeWrongFormatException()
    {
        $dateHelperClass = $this->getHelperClass();

        $dateHelperClass::getElapsedTime(new \DateTime(), 20);
    }

    /**
     * Test the "getElapsedTime" method results
     *
     * @param \Datetime $date
     * @param integer   $format
     * @param integer   $expectedResult
     *
     * @dataProvider testElapsedTimeDataProvider
     */
    public function testElapsedTime(\Datetime $date, $format, $expectedResult)
    {
        $dateHelperClass = $this->getHelperClass();

        if (null !== $format) {
            $result = $dateHelperClass::getElapsedTime($date, $format);
        } else {
            $result = $dateHelperClass::getElapsedTime($date);
        }

        $this->assertSame($expectedResult, $result);
    }

    /**
     * Get the helper class to test
     *
     * @return string
     */
    protected function getHelperClass()
    {
        $helperClass = $this->getMockClass('WillCorp\ZombieBundle\Game\Helper\Date', array('now'));
        $helperClass::staticExpects($this->any())
            ->method('now')
            ->will($this->returnValue(new \DateTime('2014-01-10 12:00:00')));

        return $helperClass;
    }

    /**
     * Data provider for the "testElapsedTime" method
     *
     * @return array
     */
    public function testElapsedTimeDataProvider()
    {
        return array(
            array(
                new \DateTime('2014-01-10 12:00:00'),
                null,
                0
            ),
            array(
                new \DateTime('2014-01-10 11:59:30'),
                Date::FORMAT_SECONDS,
                30
            ),
            array(
                new \DateTime('2014-01-10 11:45:00'),
                Date::FORMAT_MINUTES,
                15
            ),
            array(
                new \DateTime('2014-01-07 12:00:00'),
                Date::FORMAT_DAYS,
                3
            ),
            array(
                new \DateTime('2014-01-11 12:00:00'),
                Date::FORMAT_DAYS,
                1
            ),
        );
    }
}