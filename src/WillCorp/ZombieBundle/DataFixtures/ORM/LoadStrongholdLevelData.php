<?php
/**
 * This file is part of the WillCorpZombieBundle package
 *
 * (c) Yann Eugoné <yann.eugone@gmail.com> ; William Sauvan <william.sauvan@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Acme\HelloBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use WillCorp\ZombieBundle\Entity\StrongholdLevel;

/**
 * @todo: provide class description
 *
 * @author    Yann Eugoné <yann.eugone@gmail.com>
 */
class LoadStrongholdLevelData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        for ($i=1 ; $i<=10 ; $i++) {
            $level = new StrongholdLevel();
            $level
                ->setLevel($i)
                ->setCost(array(
                    'energy' => $i * 100,
                    'metal'  => $i * 100,
                ))
                ->setBuildingMaxLevel($i)
                ->setColumnsCount($i + 2)
                ->setLinesCount($i + 5);

            $manager->persist($level);

            $this->addReference('stronghold-level-' . $i, $level);
        }

        $manager->flush();
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 1;
    }
}