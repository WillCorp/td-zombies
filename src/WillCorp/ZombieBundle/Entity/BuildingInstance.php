<?php

namespace WillCorp\ZombieBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * BuildingInstance
 *
 * @ORM\Table(name="building_instance")
 * @ORM\Entity(repositoryClass="WillCorp\ZombieBundle\Repository\BuildingInstanceRepository")
 */
class BuildingInstance
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
     * @ORM\Column(name="round_start", type="integer")
     */
    private $roundStart;

    /**
     * @var integer
     *
     * @ORM\Column(name="unit_count", type="integer")
     */
    private $unitCount;

    /**
     * @var array
     *
     * @ORM\Column(name="resources", type="json_array")
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
     * @var StrongholdInstance
     *
     * @ORM\ManyToOne(targetEntity="WillCorp\ZombieBundle\Entity\StrongholdInstance", inversedBy="buildings")
     * @ORM\JoinColumn(name="stronghold_id", referencedColumnName="id")
     */
    private $stronghold;

    /**
     * @var BuildingLevel
     *
     * @ORM\ManyToOne(targetEntity="WillCorp\ZombieBundle\Entity\BuildingLevel", inversedBy="buildingInstances")
     * @ORM\JoinColumn(name="building_level_id", referencedColumnName="id")
     */
    private $level;



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
     * Set roundStart
     *
     * @param integer $roundStart
     * @return BuildingInstance
     */
    public function setRoundStart($roundStart)
    {
        $this->roundStart = $roundStart;

        return $this;
    }

    /**
     * Get roundStart
     *
     * @return integer 
     */
    public function getRoundStart()
    {
        return $this->roundStart;
    }

    /**
     * Set unitCount
     *
     * @param integer $unitCount
     * @return BuildingInstance
     */
    public function setUnitCount($unitCount)
    {
        $this->unitCount = $unitCount;

        return $this;
    }

    /**
     * Get unitCount
     *
     * @return integer 
     */
    public function getUnitCount()
    {
        return $this->unitCount;
    }

    /**
     * Set resources
     *
     * @param array $resources
     * @return BuildingInstance
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
     * @return BuildingInstance
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
     * @return BuildingInstance
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
     * Set stronghold
     *
     * @param StrongholdInstance $stronghold
     * @return BuildingInstance
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

    /**
     * Set level
     *
     * @param BuildingLevel $level
     * @return BuildingInstance
     */
    public function setLevel(BuildingLevel $level = null)
    {
        $this->level = $level;

        return $this;
    }

    /**
     * Get level
     *
     * @return BuildingLevel
     */
    public function getLevel()
    {
        return $this->level;
    }
}
