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
use WillCorp\ZombieBundle\Game\Helper\Resources;

/**
 * Class Collector
 *
 * @author Yann Eugoné <yann.eugone@gmail.com>
 */
class Collector
{
    /**
     * Collect the resources of a stronghold's buildings
     * Add the stronghold's buildings income to the stronghold's resources
     *
     * @param StrongholdInstance $stronghold The stronghold to collect resources from
     */
    public function collectStrongholdResources(StrongholdInstance $stronghold)
    {
        foreach ($stronghold->getBuildings() as $building) {
            $this->collectBuildingResources($building);
        }
    }

    /**
     * Collect the resources of a building
     * Add the building's level configured income to the building's stronghold's resources
     *
     * @param BuildingInstance $building The building to collect resources from
     */
    public function collectBuildingResources(BuildingInstance $building)
    {
        $stronghold = $building->getStronghold();

        $stronghold->setResources(Resources::addResources(
            $stronghold->getResources(),
            $building->getLevel()->getIncome()
        ));
    }
}