<?php
/**
 * This file is part of the WillCorpZombieBundle package
 *
 * (c) Yann Eugoné <yann.eugone@gmail.com> ; William Sauvan <william.sauvan@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace WillCorp\ZombieBundle\Tests\Entity;

use WillCorp\ZombieBundle\Entity\StrongholdInstance;
use WillCorp\ZombieBundle\Entity\StrongholdLevel;

/**
 * Test the game "date" helper class
 *          {@link WillCorp\ZombieBundle\Game\Helper\Date}
 *
 * @author Yann Eugoné <yann.eugone@gmail.com>
 */
class StrongholdInstanceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test the "resetColumns" method exception
     * When on a object with no level assigned
     *
     * @expectedException \LogicException
     * @expectedExceptionMessage Unable to reset columns, no level assigned to the stronghold
     */
    public function testResetColumnsNoLevelException()
    {
        $stronghold = new StrongholdInstance();

        $stronghold->resetColumns();
    }

    /**
     * Test the "resetColumns" method exception
     * When on a object with an assigned level columns count that is invalid
     *
     * @expectedException \RangeException
     * @expectedExceptionMessage Unable to reset columns, the column count of the level 3 is invalid (3)
     */
    public function testResetColumnsWrongColumnCountException()
    {
        $strongholdLevel = new StrongholdLevel();
        $strongholdLevel->setLevel(3)
            ->setColumnsCount(3);
        $stronghold = new StrongholdInstance();
        $stronghold->setLevel($strongholdLevel);

        $stronghold->resetColumns();
    }

    /**
     * Test the "resetColumns" method results
     *
     * @param StrongholdInstance $stronghold
     * @param array $expectedColumns
     *
     * @dataProvider testResetColumnsDataProvider
     */
    public function testResetColumns(StrongholdInstance $stronghold, array $expectedColumns)
    {
        $stronghold->resetColumns();

        $columns = $stronghold->getColumns();

        $this->assertSameSize($expectedColumns, $columns);
        foreach ($expectedColumns as $column => $percent) {
            $this->assertArrayHasKey($column, $columns);
            $this->assertSame($percent, $columns[$column]);
        }
    }

    /**
     * Data provider for the "testResetColumns" method
     *
     * @return array
     */
    public function testResetColumnsDataProvider()
    {
        $data = array();

        $strongholdLevel = new StrongholdLevel();
        $strongholdLevel->setColumnsCount(1);
        $stronghold = new StrongholdInstance();
        $stronghold->setLevel($strongholdLevel);
        $data[] = array(
            $stronghold,
            array(1 => 100)
        );

        $strongholdLevel = new StrongholdLevel();
        $strongholdLevel->setColumnsCount(5);
        $stronghold = new StrongholdInstance();
        $stronghold->setLevel($strongholdLevel);
        $data[] = array(
            $stronghold,
            array(1 => 20, 2 => 20, 3 => 20, 4 => 20, 5 => 20)
        );

        $strongholdLevel = new StrongholdLevel();
        $strongholdLevel->setColumnsCount(10);
        $stronghold = new StrongholdInstance();
        $stronghold->setLevel($strongholdLevel);
        $data[] = array(
            $stronghold,
            array(1 => 10, 2 => 10, 3 => 10, 4 => 10, 5 => 10, 6 => 10, 7 => 10, 8 => 10, 9 => 10, 10 => 10)
        );

        return $data;
    }
}