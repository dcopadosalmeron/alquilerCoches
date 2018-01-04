<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class Coche
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @var int
     */
    private $id;

    /**
     * @ORM\Column(type="string", unique=true)
     * @var string
     */
    private $matricula;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    private $marca;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    private $modelo;

    /**
     * @ORM\Column(type="decimal", precision=7, scale=2)
     * @var double
     */
    private $precioDia;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Ciudad", inversedBy="coches")
     */
    protected $ciudad;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Alquiler", mappedBy="coche")
     */
    protected $alquileres;

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
     * Set matricula
     *
     * @param string $matricula
     *
     * @return Coche
     */
    public function setMatricula($matricula)
    {
        $this->matricula = $matricula;

        return $this;
    }

    /**
     * Get matricula
     *
     * @return string
     */
    public function getMatricula()
    {
        return $this->matricula;
    }

    /**
     * Set marca
     *
     * @param string $marca
     *
     * @return Coche
     */
    public function setMarca($marca)
    {
        $this->marca = $marca;

        return $this;
    }

    /**
     * Get marca
     *
     * @return string
     */
    public function getMarca()
    {
        return $this->marca;
    }

    /**
     * Set modelo
     *
     * @param string $modelo
     *
     * @return Coche
     */
    public function setModelo($modelo)
    {
        $this->modelo = $modelo;

        return $this;
    }

    /**
     * Get modelo
     *
     * @return string
     */
    public function getModelo()
    {
        return $this->modelo;
    }

    /**
     * Set precioDia
     *
     * @param string $precioDia
     *
     * @return Coche
     */
    public function setPrecioDia($precioDia)
    {
        $this->precioDia = $precioDia;

        return $this;
    }

    /**
     * Get precioDia
     *
     * @return string
     */
    public function getPrecioDia()
    {
        return $this->precioDia;
    }

    /**
     * Set ciudad
     *
     * @param \AppBundle\Entity\Ciudad $ciudad
     *
     * @return Coche
     */
    public function setCiudad(\AppBundle\Entity\Ciudad $ciudad = null)
    {
        $this->ciudad = $ciudad;

        return $this;
    }

    /**
     * Get ciudad
     *
     * @return \AppBundle\Entity\Ciudad
     */
    public function getCiudad()
    {
        return $this->ciudad;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->alquileres = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add alquilere
     *
     * @param \AppBundle\Entity\Alquiler $alquilere
     *
     * @return Coche
     */
    public function addAlquilere(\AppBundle\Entity\Alquiler $alquilere)
    {
        $this->alquileres[] = $alquilere;

        return $this;
    }

    /**
     * Remove alquilere
     *
     * @param \AppBundle\Entity\Alquiler $alquilere
     */
    public function removeAlquilere(\AppBundle\Entity\Alquiler $alquilere)
    {
        $this->alquileres->removeElement($alquilere);
    }

    /**
     * Get alquileres
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAlquileres()
    {
        return $this->alquileres;
    }
}
