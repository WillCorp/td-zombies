<?php

namespace WillCorp\ZombieBundle\Tests\Game\Helper;

use WillCorp\ZombieBundle\Game\Helper\Resources;

/**
 * Test the game helper class
 *          {@link WillCorp\ZombieBundle\Game\Helper\Resources}
 *
 * @author Yann Eugoné <yann.eugone@gmail.com>
 */
class ResourcesTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test the "hasEnoughResources" method results
     *
     * @param array   $disposal
     * @param array   $cost
     * @param boolean $enough
     *
     * @dataProvider testHasEnoughResourcesDataProvider
     */
    public function testHasEnoughResources(array $disposal, array $cost, $enough)
    {
        $result = Resources::hasEnoughResources($disposal, $cost);

        if ($enough) {
            $this->assertTrue($result);
        } else {
            $this->assertFalse($result);
        }
    }

    /**
     * Test the "subtractResources" method results
     *
     * @param array $supply
     * @param array $cost
     * @param array $rest
     *
     * @dataProvider testSubtractResourcesDataProvider
     */
    public function testSubtractResources(array $supply, array $cost, array $rest)
    {
        $result = Resources::subtractResources($supply, $cost);

        $this->assertSameSize($rest, $result);
        foreach ($rest as $resource => $value) {
            $this->assertArrayHasKey($resource, $result);
            $this->assertSame($value, $result[$resource]);
        }
    }

    /**
     * Test the "subtractResources" method exceptions
     *
     * @param array  $supply
     * @param array  $cost
     * @param string $exceptionName
     * @param string $exceptionMessage
     *
     * @dataProvider testSubtractResourcesExceptionsDataProvider
     */
    public function testSubtractResourcesExceptions(array $supply, array $cost, $exceptionName, $exceptionMessage)
    {
        $this->setExpectedException($exceptionName, $exceptionMessage);

        Resources::subtractResources($supply, $cost);
    }

    /**
     * Test the "subtractResources" method results
     *
     * @param array $supply
     * @param array $extra
     * @param array $expectedResult
     *
     * @dataProvider testAddResourcesDataProvider
     */
    public function testAddResources(array $supply, array $extra, array $expectedResult)
    {
        $result = Resources::addResources($supply, $extra);

        $this->assertSameSize($expectedResult, $result);
        foreach ($expectedResult as $resource => $value) {
            $this->assertArrayHasKey($resource, $result);
            $this->assertSame($value, $result[$resource]);
        }
    }

    /**
     * Data provider for the "testHasEnoughResources" method
     *
     * @return array
     */
    public function testHasEnoughResourcesDataProvider()
    {
        return array(
            //Single resources
            array(
                array(Resources::ENERGY => 100),
                array(Resources::ENERGY => 50),
                true
            ),
            array(
                array(Resources::ENERGY => 50),
                array(Resources::ENERGY => 50),
                true
            ),
            array(
                array(Resources::ENERGY => 50),
                array(Resources::ENERGY => 100),
                false
            ),

            //Multiple resources (same keys)
            array(
                array(Resources::ENERGY => 100, Resources::METAL => 100),
                array(Resources::ENERGY => 50, Resources::METAL => 50),
                true
            ),
            array(
                array(Resources::ENERGY => 50, Resources::METAL => 50),
                array(Resources::ENERGY => 50, Resources::METAL => 50),
                true
            ),
            array(
                array(Resources::ENERGY => 50, Resources::METAL => 50),
                array(Resources::ENERGY => 100, Resources::METAL => 100),
                false
            ),

            //Not same keys resources
            array(
                array(Resources::ENERGY => 50),
                array(Resources::METAL => 100),
                false
            ),
            array(
                array(Resources::ENERGY => 50),
                array(Resources::METAL => 100, Resources::ENERGY => 50),
                false
            ),
        );
    }

    /**
     * Data provider for the "testSubtractResources" method
     *
     * @return array
     */
    public function testSubtractResourcesDataProvider()
    {
        return array(
            //Single resources
            array(
                array(Resources::ENERGY => 100),
                array(Resources::ENERGY => 50),
                array(Resources::ENERGY => 50)
            ),

            //Multiple resources (same keys)
            array(
                array(Resources::ENERGY => 100, Resources::METAL => 100),
                array(Resources::ENERGY => 50, Resources::METAL => 25),
                array(Resources::ENERGY => 50, Resources::METAL => 75)
            ),

            //Multiple resources (not same keys)
            array(
                array(Resources::ENERGY => 100, Resources::METAL => 100),
                array(Resources::ENERGY => 50),
                array(Resources::ENERGY => 50, Resources::METAL => 100)
            ),
        );
    }

    /**
     * Data provider for the "testSubtractResourcesExceptions" method
     *
     * @return array
     */
    public function testSubtractResourcesExceptionsDataProvider()
    {
        $data = array();
        foreach ($this->testHasEnoughResourcesDataProvider() as $_data) {
            if (!$_data[2]) {
                $data[] = array(
                    $_data[0],
                    $_data[1],
                    'Exception',
                    'There is not enough resource for subtraction'
                );
            }
        }

        return $data;
    }

    /**
     * Data provider for the "testAddResources" method
     *
     * @return array
     */
    public function testAddResourcesDataProvider()
    {
        return array(
            //Single resources
            array(
                array(Resources::ENERGY => 100),
                array(Resources::ENERGY => 50),
                array(Resources::ENERGY => 150)
            ),

            //Multiple resources (same keys)
            array(
                array(Resources::ENERGY => 100, Resources::METAL => 100),
                array(Resources::ENERGY => 50, Resources::METAL => 25),
                array(Resources::ENERGY => 150, Resources::METAL => 125)
            ),

            //Multiple resources (not same keys)
            array(
                array(Resources::ENERGY => 100, Resources::METAL => 100),
                array(Resources::ENERGY => 50),
                array(Resources::ENERGY => 150, Resources::METAL => 100)
            ),
            array(
                array(Resources::ENERGY => 50),
                array(Resources::ENERGY => 100, Resources::METAL => 100),
                array(Resources::ENERGY => 150, Resources::METAL => 100)
            ),
        );
    }
}