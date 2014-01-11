<?php

namespace WillCorp\ZombieBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Building
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="WillCorp\ZombieBundle\Repository\BuildingRepository")
 */
class Building
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=255)
     */
    private $image;

    /**
     * @var BuildingLevel[]
     *
     * @ORM\OneToMany(targetEntity="WillCorp\ZombieBundle\Entity\BuildingLevel", mappedBy="building")
     */
    private $buildingInstances;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->buildingInstances = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set name
     *
     * @param string $name
     * @return Building
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Building
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set image
     *
     * @param string $image
     * @return Building
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string 
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Add buildingInstances
     *
     * @param BuildingLevel $buildingInstances
     * @return Building
     */
    public function addBuildingInstance(BuildingLevel $buildingInstances)
    {
        $this->buildingInstances[] = $buildingInstances;

        return $this;
    }

    /**
     * Remove buildingInstances
     *
     * @param BuildingLevel $buildingInstances
     */
    public function removeBuildingInstance(BuildingLevel $buildingInstances)
    {
        $this->buildingInstances->removeElement($buildingInstances);
    }

    /**
     * Get buildingInstances
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getBuildingInstances()
    {
        return $this->buildingInstances;
    }
}
