<?php
/**
 * This file is part of the WillCorpZombieBundle package
 *
 * (c) Yann Eugoné <yann.eugone@gmail.com> ; William Sauvan <william.sauvan@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace WillCorp\ZombieBundle\Game\Processor;

use WillCorp\ZombieBundle\Entity\BuildingInstance;
use WillCorp\ZombieBundle\Entity\StrongholdInstance;
use WillCorp\ZombieBundle\Game\Helper\Date as DateHelper;

/**
 * Collector's abstract classes
 *
 * @author Yann Eugoné <yann.eugone@gmail.com>
 */
abstract class Collector
{
    /**
     * Perform a collect of a stronghold's buildings
     *
     * @param StrongholdInstance $stronghold The stronghold to collect from
     */
    abstract public function collectStronghold(StrongholdInstance $stronghold);

    /**
     * Perform collect of a building
     *
     * @param BuildingInstance $building The building to collect from
     */
    abstract public function collectBuilding(BuildingInstance $building);

    /**
     * Get the count of collect available for a given $building
     *
     * @param BuildingInstance $building
     * @param integer          $interval
     *
     * @return integer
     */
    protected function getCollectCount(BuildingInstance $building, $interval)
    {
        return floor(DateHelper::getElapsedTime($building->getUpdatedAt(), DateHelper::FORMAT_SECONDS) / $interval);
    }
}