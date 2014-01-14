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
     * Test the "hasEnoughResources" method
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
}