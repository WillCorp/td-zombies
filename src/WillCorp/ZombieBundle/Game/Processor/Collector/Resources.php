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
use WillCorp\ZombieBundle\Game\Helper\Resources as ResourcesHelper;
use WillCorp\ZombieBundle\Game\Processor\Collector;

/**
 * Resources collector
 *
 * @author Yann Eugoné <yann.eugone@gmail.com>
 */
class Resources extends Collector
{
    const COLLECTOR_NAME = 'resources';

    const COLLECT_INTERVAL = 300;

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
        $stronghold = $building->getStronghold();

        $collectCount = $this->getCollectCount($building, static::COLLECT_INTERVAL);

        if ($collectCount > 0) {
            $stronghold->setResources(ResourcesHelper::addResources(
                $stronghold->getResources(),
                ResourcesHelper::multiplyResources($building->getLevel()->getIncome(), $collectCount)
            ));

            $building->setUpdatedAt(new \DateTime());
        }
    }
}