<?php

namespace Chaplean\Bundle\LocationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

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
 * @ORM\Entity
 *
 * @JMS\ExclusionPolicy("all")
 */
class City extends Location
{
    /**
     * @var integer
     *
     * @ORM\Column(type="integer", length=5, nullable=false, name="zipcode", options={"unsigned":true})
     *
     * @JMS\Expose
     * @JMS\Groups({"city_zipcode"})
     */
    private $zipcode;

    /**
     * @ORM\Column(type="decimal", length=10, nullable=false, name="latitude", precision=10, scale=7)
     */
    private $latitude;

    /**
     * @ORM\Column(type="decimal", length=10, nullable=false, name="longitude", precision=10, scale=7)
     */
    private $longitude;

    /**
     * @var Department
     *
     * @ORM\ManyToOne(targetEntity="Chaplean\Bundle\LocationBundle\Entity\Department", inversedBy="cities")
     * @ORM\JoinColumn(name="department_id", referencedColumnName="id", nullable=false)
     *
     * @JMS\Expose
     * @JMS\MaxDepth(depth=1)
     * @JMS\Groups({"city_department"})
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
}
