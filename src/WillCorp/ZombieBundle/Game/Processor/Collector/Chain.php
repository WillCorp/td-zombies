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
 * Chain collector
 *
 * @author Yann Eugoné <yann.eugone@gmail.com>
 */
class Chain extends Collector
{
    /**
     * The collectors objects
     * @var Collector[]
     */
    protected $collectors;

    /**
     * Add a collector
     *
     * @param string    $name      The collector name
     * @param Collector $collector The collector object to add
     *
     * @return Chain
     */
    public function addCollector($name, Collector $collector)
    {
        $this->collectors[$name] = $collector;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function collectStronghold(StrongholdInstance $stronghold)
    {
        foreach ($this->collectors as $collector) {
            $collector->collectStronghold($stronghold);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function collectBuilding(BuildingInstance $building)
    {
        foreach ($this->collectors as $collector) {
            $collector->collectBuilding($building);
        }
    }

    /**
     * Process a stronghold collect of a single named collector
     *
     * @param string             $name       The collector name
     * @param StrongholdInstance $stronghold The stronghold to use
     *
     * @throws \Exception
     */
    public function collectStrongholdSingle($name, StrongholdInstance $stronghold)
    {
        if (!array_key_exists($name, $this->collectors)) {
            throw new \Exception(sprintf('The collector "%s" does not exists', $name));
        }

        $this->collectors[$name]->collectStronghold($stronghold);
    }

    /**
     * Process a building collect of a single named collector
     *
     * @param string           $name     The collector name
     * @param BuildingInstance $building The building to use
     *
     * @throws \Exception
     */
    public function collectBuildingSingle($name, BuildingInstance $building)
    {
        if (!array_key_exists($name, $this->collectors)) {
            throw new \Exception(sprintf('The collector "%s" does not exists', $name));
        }

        $this->collectors[$name]->collectBuilding($building);
    }
}