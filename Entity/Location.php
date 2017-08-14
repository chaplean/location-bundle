<?php
namespace Chaplean\Bundle\LocationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Entity
 * @ORM\Table(name="cl_location", indexes={@ORM\Index(name="location_name_INDEX", columns={"name"})})
 *
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="dtype", type="string")
 * @ORM\DiscriminatorMap({
 *     "city":"Chaplean\Bundle\LocationBundle\Entity\City",
 *     "department":"Chaplean\Bundle\LocationBundle\Entity\Department",
 *     "region":"Chaplean\Bundle\LocationBundle\Entity\Region"
 * })
 *
 * @JMS\ExclusionPolicy("all")
 */
abstract class Location
{
    /**
     * @var integer
     *
     * @ORM\Id
     * @ORM\Column(type="integer", options={"unsigned":true})
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @JMS\Expose
     * @JMS\Groups({"location_id"})
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=50, nullable=false)
     *
     * @JMS\Expose
     * @JMS\Groups({"location_name"})
     */
    protected $name;

    /**
     * Get id.
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set name.
     *
     * @param string $name
     *
     * @return Location
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Returns wether or not this Location is contained by $location
     *
     * @param Location|null $location
     *
     * @return boolean
     */
    public function isLocatedIn(Location $location = null)
    {
        return $location !== null
            ? $location->containsLocation($this)
            : false;
    }

    /**
     * Returns wether or not the given $location contains this Location
     *
     * @param Location|null $location
     *
     * @return boolean
     */
    abstract public function containsLocation(Location $location = null);

    /**
     * Get the Region associated with this Location if any
     *
     * @return Region|null
     */
    abstract public function getRegion();

    /**
     * Get the Department associated with this Location if any
     *
     * @return Department|null
     */
    abstract public function getDepartment();

    /**
     * Get the City associated with this Location if any
     *
     * @return City|null
     */
    abstract public function getCity();

    /**
     * @param Location|null $location
     *
     * @return bool
     */
    protected function compareIds(Location $location = null)
    {
        return $location !== null
            ? $this->getId() === $location->getId()
            : false;
    }
}
