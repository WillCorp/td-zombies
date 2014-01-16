<?php
/**
 * This file is part of the WillCorpZombieBundle package
 *
 * (c) Yann Eugoné <yann.eugone@gmail.com> ; William Sauvan <william.sauvan@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace WillCorp\ZombieBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serialize;

/**
 * StrongholdInstance
 *
 * @ORM\Table(name="stronghold_instance")
 * @ORM\Entity(repositoryClass="WillCorp\ZombieBundle\Repository\StrongholdInstanceRepository")
 *
 * @Serialize\ExclusionPolicy("all")
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
     *
     * @Serialize\Expose
     */
    private $columns;

    /**
     * @var array
     *
     * @ORM\Column(name="resources", type="json_array")
     *
     * @Serialize\Expose
     */
    private $resources;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime")
     */
    private $updatedAt;

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
     *
     * @Serialize\Expose
     */
    private $square;

    /**
     * @var StrongholdLevel
     *
     * @ORM\ManyToOne(targetEntity="WillCorp\ZombieBundle\Entity\StrongholdLevel", inversedBy="strongholdInstances")
     * @ORM\JoinColumn(name="stronghold_level_id", referencedColumnName="id")
     *
     * @Serialize\Expose
     */
    private $level;

    /**
     * @var BuildingInstance[]
     *
     * @ORM\OneToMany(targetEntity="WillCorp\ZombieBundle\Entity\BuildingInstance", mappedBy="stronghold")
     * @Serialize\Expose
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
     * Set resources
     *
     * @param array $resources
     * @return StrongholdInstance
     */
    public function setResources($resources)
    {
        $this->resources = $resources;

        return $this;
    }

    /**
     * Get resources
     *
     * @return array
     */
    public function getResources()
    {
        return $this->resources;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return StrongholdInstance
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return StrongholdInstance
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
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
     * @param BuildingInstance $building
     * @return StrongholdInstance
     */
    public function addBuilding(BuildingInstance $building)
    {
        $building->setStronghold($this);
        $this->buildings[] = $building;

        return $this;
    }

    /**
     * Remove buildings
     *
     * @param BuildingInstance $building
     */
    public function removeBuilding(BuildingInstance $building)
    {
        $this->buildings->removeElement($building);
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
