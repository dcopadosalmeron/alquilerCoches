<?php
namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Validator\Constraints as MyAssert;

/**
 * @ORM\Table(name="cliente", uniqueConstraints={@ORM\UniqueConstraint(name="cliente_unique", columns={"dni"})})
 * @ORM\Entity()
 */
class Cliente
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @var int
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank(message="El DNI es obligatorio.")
     * @MyAssert\dni()
     * @var string
     */
    private $dni;

    /**
     * @ORM\Column(type="string")
     * @Assert\Regex(
     *     pattern="/^[a-zA-ZñÑ\sáéíóúüÁÉÍÓÚÜ]+$/",
     *     message="El nombre no puede contener números o símbolos."
     * )
     * @Assert\Length(
     *      max = 30,
     *      maxMessage = "El nombre no puede tener más de 30 caracteres."
     * )
     * @Assert\NotBlank(message="El nombre es obligatorio.")
     * @var string
     */
    private $nombre;

    /**
     * @ORM\Column(type="string")
     * @Assert\Regex(
     *     pattern="/^[a-zA-ZñÑ\sáéíóúüÁÉÍÓÚÜ]+\s{1}[a-zA-ZñÑ\sáéíóúüÁÉÍÓÚÜ]+$/",
     *     message="Los apellidos no pueden contener números o símbolos (al menos dos palabras)."
     * )
     * @Assert\Length(
     *      max = 50,
     *      maxMessage = "Los apellidos no pueden tener más de 50 caracteres."
     * )
     * @Assert\NotBlank(message="Los apellidos son obligatorios.")
     * @var string
     */
    private $apellidos;

    /**
     * @ORM\Column(type="date")
     * @Assert\NotBlank(message="La fecha es obligatoria.")
     * @Assert\Regex(
     *     pattern="/^(0[1-9]|[1-2][0-9]|3[0-1])-(0[1-9]|1[0-2])-[0-9]{4}$/",
     *     message="El formato de fecha no es válido."
     * )
     * @var \DateTime
     */
    private $fechaNacimiento;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Alquiler", mappedBy="cliente")
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
     * Set dni
     *
     * @param string $dni
     *
     * @return Cliente
     */
    public function setDni($dni)
    {
        $this->dni = $dni;

        return $this;
    }

    /**
     * Get dni
     *
     * @return string
     */
    public function getDni()
    {
        return $this->dni;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return Cliente
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set apellidos
     *
     * @param string $apellidos
     *
     * @return Cliente
     */
    public function setApellidos($apellidos)
    {
        $this->apellidos = $apellidos;

        return $this;
    }

    /**
     * Get apellidos
     *
     * @return string
     */
    public function getApellidos()
    {
        return $this->apellidos;
    }

    /**
     * Set fechaNacimiento
     *
     * @param \DateTime $fechaNacimiento
     *
     * @return Cliente
     */
    public function setFechaNacimiento($fechaNacimiento)
    {
        $this->fechaNacimiento = $fechaNacimiento;

        return $this;
    }

    /**
     * Get fechaNacimiento
     *
     * @return \DateTime
     */
    public function getFechaNacimiento()
    {
        return $this->fechaNacimiento;
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
     * @return Cliente
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
