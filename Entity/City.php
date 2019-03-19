<?php

namespace Chaplean\Bundle\LocationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;

/**
 * City
 *
 * @ORM\Table(
 *     name="cl_city",
 *     indexes={
 *         @ORM\Index(name="city_zipcode_INDEX", columns={"zipcode"}),
 *         @ORM\Index(name="city_latitude_INDEX", columns={"latitude"}),
 *         @ORM\Index(name="city_longitude_INDEX", columns={"longitude"})
 *     }
 * )
 * @ORM\Entity(repositoryClass="Chaplean\Bundle\LocationBundle\Repository\CityRepository")
 */
class City extends Location
{
    /**
     * @var integer
     *
     * @ORM\Column(type="integer", length=5, nullable=false, name="zipcode", options={"unsigned":true})
     *
     * @Groups({"city_zipcode"})
     */
    private $zipcode;

    /**
     * @var float
     *
     * @ORM\Column(type="decimal", length=10, nullable=false, name="latitude", precision=10, scale=7)
     */
    private $latitude;

    /**
     * @var float
     *
     * @ORM\Column(type="decimal", length=10, nullable=false, name="longitude", precision=10, scale=7)
     */
    private $longitude;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=5, nullable=true, name="code_insee")
     */
    private $codeInsee;

    /**
     * @var Department
     *
     * @ORM\ManyToOne(targetEntity="Chaplean\Bundle\LocationBundle\Entity\Department", inversedBy="cities")
     * @ORM\JoinColumn(name="department_id", referencedColumnName="id", nullable=false)
     *
     * @Groups({"city_department"})
     */
    private $department;

    /**
     * Set zipcode
     *
     * @param integer $zipcode
     * @return City
     */
    public function setZipcode($zipcode)
    {
        $this->zipcode = $zipcode;

        return $this;
    }

    /**
     * Get zipcode
     *
     * @return integer
     */
    public function getZipcode()
    {
        return $this->zipcode;
    }

    /**
     * Get latitude.
     *
     * @return float
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Set latitude.
     *
     * @param float $latitude
     *
     * @return self
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * Get longitude.
     *
     * @return float
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Set longitude.
     *
     * @param float $longitude
     *
     * @return self
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * Set department
     *
     * @param Department $department
     * @return City
     */
    public function setDepartment(Department $department = null)
    {
        $this->department = $department;

        return $this;
    }

    /**
     * Get department
     *
     * @return Department
     */
    public function getDepartment()
    {
        return $this->department;
    }

    /**
     * Get Region
     *
     * @return Region
     */
    public function getRegion()
    {
        return $this->department->getRegion();
    }

    /**
     * @return string
     */
    public function getZipcodeString()
    {
        return str_pad((string) $this->getZipcode(), 5, '0', STR_PAD_LEFT);
    }

    /**
     * Get the City associated with this Location if any
     *
     * @return City|null
     */
    public function getCity()
    {
        return $this;
    }

    /**
     * @return string
     */
    public function getCodeInsee()
    {
        return $this->codeInsee;
    }

    /**
     * @param string $codeInsee
     *
     * @return self
     */
    public function setCodeInsee($codeInsee)
    {
        $this->codeInsee = $codeInsee;

        return $this;
    }

    /**
     * Returns wether or not the given $location contains this Location
     *
     * @param Location|null $location
     * @param boolean       $strict
     *
     * @return boolean
     */
    public function containsLocation(Location $location = null, $strict = true)
    {
        if ($location instanceof City && !$strict) {
            return $this->name === $location->getName() && $this->getDepartment()->getId() === $location->getDepartment()->getId();
        }

        return $this->compareIds(($location !== null ? $location->getCity() : null));
    }
}
