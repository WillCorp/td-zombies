<?php

namespace WillCorp\ZombieBundle\Entity;

use FOS\UserBundle\Model\User;
use Doctrine\ORM\Mapping as ORM;

/**
 * Player
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="WillCorp\ZombieBundle\Repository\PlayerRepository")
 */
class Player extends User
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var StrongholdInstance
     *
     * @ORM\OneToOne(targetEntity="WillCorp\ZombieBundle\Entity\StrongholdInstance", mappedBy="player")
     */
    private $stronghold;


    /**
     * Set stronghold
     *
     * @param StrongholdInstance $stronghold
     * @return Player
     */
    public function setStronghold(StrongholdInstance $stronghold = null)
    {
        $this->stronghold = $stronghold;

        return $this;
    }

    /**
     * Get stronghold
     *
     * @return StrongholdInstance
     */
    public function getStronghold()
    {
        return $this->stronghold;
    }
}
