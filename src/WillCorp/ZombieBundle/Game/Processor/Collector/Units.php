<?php
/**
 * This file is part of the WillCorpZombieBundle package
 *
 * (c) Yann Eugoné <yann.eugone@gmail.com> ; William Sauvan <william.sauvan@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace WillCorp\ZombieBundle\Game\Processor\Collector;

use WillCorp\ZombieBundle\Entity\BuildingInstance;
use WillCorp\ZombieBundle\Entity\StrongholdInstance;
use WillCorp\ZombieBundle\Game\Processor\Collector;

/**
 * Units collector
 *
 * @author Yann Eugoné <yann.eugone@gmail.com>
 */
class Units extends Collector
{
    const COLLECTOR_NAME = 'units';

    /**
     * {@inheritdoc}
     */
    public function collectStronghold(StrongholdInstance $stronghold)
    {
        foreach ($stronghold->getBuildings() as $building) {
            $this->collectBuilding($building);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function collectBuilding(BuildingInstance $building)
    {
        $buildingLevel = $building->getLevel();

        $collectCount = $this->getCollectCount($building, $buildingLevel->getUnitCooldown());

        if ($collectCount > 0) {
            $allowedCollectCount = $buildingLevel->getUnitCountLimit() - $building->getUnitCount();

            if ($allowedCollectCount > 0) {
                $building->setUnitCount(
                    $building->getUnitCount() + min($allowedCollectCount, $collectCount)
                );
            }
        }
    }
} 