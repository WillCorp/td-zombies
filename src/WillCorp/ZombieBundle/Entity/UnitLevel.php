<?php

namespace WillCorp\ZombieBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serialize;

/**
 * UnitLevel
 *
 * @ORM\Table(name="unit_level")
 * @ORM\Entity(repositoryClass="WillCorp\ZombieBundle\Repository\UnitLevelRepository")
 *
 * @Serialize\ExclusionPolicy("all")
 */
class UnitLevel
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
     * @var integer
     *
     * @ORM\Column(name="level", type="integer")
     *
     * @Serialize\Expose
     */
    private $level;

    /**
     * @var integer
     *
     * @ORM\Column(name="speed", type="integer")
     *
     * @Serialize\Expose
     */
    private $speed;

    /**
     * @var integer
     *
     * @ORM\Column(name="health", type="integer")
     *
     * @Serialize\Expose
     */
    private $health;

    /**
     * @var integer
     *
     * @ORM\Column(name="damages", type="integer")
     *
     * @Serialize\Expose
     */
    private $damages;

    /**
     * @var Unit
     *
     * @ORM\ManyToOne(targetEntity="WillCorp\ZombieBundle\Entity\Unit", inversedBy="levels")
     * @ORM\JoinColumn(name="unit_id", referencedColumnName="id")
     *
     * @Serialize\Expose
     */
    private $unit;

    /**
     * @var BuildingLevel
     *
     * @ORM\OneToMany(targetEntity="WillCorp\ZombieBundle\Entity\BuildingLevel", mappedBy="unit")
     */
    private $building;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->building = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set level
     *
     * @param integer $level
     * @return UnitLevel
     */
    public function setLevel($level)
    {
        $this->level = $level;

        return $this;
    }

    /**
     * Get level
     *
     * @return integer 
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * Set speed
     *
     * @param integer $speed
     * @return UnitLevel
     */
    public function setSpeed($speed)
    {
        $this->speed = $speed;

        return $this;
    }

    /**
     * Get speed
     *
     * @return integer 
     */
    public function getSpeed()
    {
        return $this->speed;
    }

    /**
     * Set health
     *
     * @param integer $health
     * @return UnitLevel
     */
    public function setHealth($health)
    {
        $this->health = $health;

        return $this;
    }

    /**
     * Get health
     *
     * @return integer 
     */
    public function getHealth()
    {
        return $this->health;
    }

    /**
     * Set damages
     *
     * @param integer $damages
     * @return UnitLevel
     */
    public function setDamages($damages)
    {
        $this->damages = $damages;

        return $this;
    }

    /**
     * Get damages
     *
     * @return integer 
     */
    public function getDamages()
    {
        return $this->damages;
    }

    /**
     * Set unit
     *
     * @param Unit $unit
     * @return UnitLevel
     */
    public function setUnit(Unit $unit = null)
    {
        $this->unit = $unit;

        return $this;
    }

    /**
     * Get unit
     *
     * @return Unit
     */
    public function getUnit()
    {
        return $this->unit;
    }

    /**
     * Add building
     *
     * @param BuildingLevel $building
     * @return UnitLevel
     */
    public function addBuilding(BuildingLevel $building)
    {
        $this->building[] = $building;

        return $this;
    }

    /**
     * Remove building
     *
     * @param BuildingLevel $building
     */
    public function removeBuilding(BuildingLevel $building)
    {
        $this->building->removeElement($building);
    }

    /**
     * Get building
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getBuilding()
    {
        return $this->building;
    }
}
