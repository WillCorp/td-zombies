<?php

namespace WillCorp\ZombieBundle\Tests\Game\Helper;

use WillCorp\ZombieBundle\Game\Helper\Moves;

/**
 * Test the game helper class
 *          {@link WillCorp\ZombieBundle\Game\Helper\Moves}
 *
 * @author Sauvan William <sauvan.william@gmail.com>
 */
class MovesTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test the "canMoveBuilding" method results
     *
     * @param int $b1_roudstart
     * @param int $b1_columnstart
     * @param int $b1_roundcount
     * @param int $b1_columncount
     * @param int $b2_roudstart
     * @param int $b2_columnstart
     * @param int $b2_roundcount
     * @param int $b2_columncount
     *
     * @dataProvider testCanMoveBuildingDataProvider
     */
    public function testCanMoveBuilding($b1_roudstart, $b1_columnstart, $b1_roundcount, $b1_columncount, $b2_roudstart, $b2_columnstart, $b2_roundcount, $b2_columncount, $can)
    {
        $result = Moves::canMoveBuilding($b1_roudstart, $b1_columnstart, $b1_roundcount, $b1_columncount, $b2_roudstart, $b2_columnstart, $b2_roundcount, $b2_columncount);

        if ($can) {
            $this->assertTrue($result);
        } else {
            $this->assertFalse($result);
        }
    }
    
    /**
     * Data provider for the "testCanMoveBuilding" method
     *
     * @return array
     */
    public function testCanMoveBuildingDataProvider()
    {
        return array(
            //another building
            array(
                1,
                1,
                1,
                1,
                2,
                2,
                1,
                1,
                true
            ),
            //upper right
            array(
                1,
                1,
                3,
                3,
                3,
                3,
                1,
                1,
                false
            ),
            //upper left
            array(
                1,
                1,
                3,
                3,
                3,
                1,
                1,
                1,
                false
            ),
            //lowest right
            array(
                1,
                1,
                3,
                3,
                1,
                3,
                1,
                1,
                false
            ),
            //lowest left
            array(
                1,
                1,
                3,
                3,
                1,
                1,
                1,
                1,
                false
            ),
        );
    }
    

}