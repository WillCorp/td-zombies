<?php

namespace WillCorp\ZombieBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * StrongholdInstance
 *
 * @ORM\Table(name="stronghold_instance")
 * @ORM\Entity(repositoryClass="WillCorp\ZombieBundle\Repository\StrongholdInstanceRepository")
 */
class StrongholdInstance
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var array
     *
     * @ORM\Column(name="columns", type="json_array")
     */
    private $columns;

    /**
     * @var Player
     *
     * @ORM\OneToOne(targetEntity="WillCorp\ZombieBundle\Entity\Player", inversedBy="stronghold")
     * @ORM\JoinColumn(name="player_id", referencedColumnName="id")
     */
    private $player;

    /**
     * @var Square
     *
     * @ORM\OneToOne(targetEntity="WillCorp\ZombieBundle\Entity\Square", inversedBy="stronghold")
     * @ORM\JoinColumn(name="square_id", referencedColumnName="id")
     */
    private $square;

    /**
     * @var StrongholdLevel
     *
     * @ORM\ManyToOne(targetEntity="WillCorp\ZombieBundle\Entity\StrongholdLevel", inversedBy="strongholdInstances")
     * @ORM\JoinColumn(name="stronghold_level_id", referencedColumnName="id")
     */
    private $level;

    /**
     * @var BuildingInstance[]
     *
     * @ORM\OneToMany(targetEntity="WillCorp\ZombieBundle\Entity\BuildingInstance", mappedBy="stronghold")
     */
    private $buildings;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->buildings = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set columns
     *
     * @param array $columns
     * @return StrongholdInstance
     */
    public function setColumns($columns)
    {
        $this->columns = $columns;

        return $this;
    }

    /**
     * Get columns
     *
     * @return array 
     */
    public function getColumns()
    {
        return $this->columns;
    }

    /**
     * Set player
     *
     * @param Player $player
     * @return StrongholdInstance
     */
    public function setPlayer(Player $player = null)
    {
        $this->player = $player;

        return $this;
    }

    /**
     * Get player
     *
     * @return Player
     */
    public function getPlayer()
    {
        return $this->player;
    }

    /**
     * Set square
     *
     * @param Square $square
     * @return StrongholdInstance
     */
    public function setSquare(Square $square = null)
    {
        $this->square = $square;

        return $this;
    }

    /**
     * Get square
     *
     * @return Square
     */
    public function getSquare()
    {
        return $this->square;
    }

    /**
     * Set level
     *
     * @param StrongholdLevel $level
     * @return StrongholdInstance
     */
    public function setLevel(StrongholdLevel $level = null)
    {
        $this->level = $level;

        return $this;
    }

    /**
     * Get level
     *
     * @return StrongholdLevel
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * Add buildings
     *
     * @param BuildingInstance $buildings
     * @return StrongholdInstance
     */
    public function addBuilding(BuildingInstance $buildings)
    {
        $this->buildings[] = $buildings;

        return $this;
    }

    /**
     * Remove buildings
     *
     * @param BuildingInstance $buildings
     */
    public function removeBuilding(BuildingInstance $buildings)
    {
        $this->buildings->removeElement($buildings);
    }

    /**
     * Get buildings
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getBuildings()
    {
        return $this->buildings;
    }
}
