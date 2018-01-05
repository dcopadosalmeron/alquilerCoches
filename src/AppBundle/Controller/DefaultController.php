<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Alquiler;
use AppBundle\Entity\Ciudad;
use AppBundle\Entity\Cliente;
use AppBundle\Entity\Coche;
use AppBundle\Form\Type\ClienteType;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\NotBlank;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="inicio")
     */
    public function indexAction(Request $request)
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        //Establecemos la variable de sesión alquiler en nula
        $session = $this->get("session");
        $session->set('alquiler', null);

        //Primer formulario
        $form = $this->createFormBuilder()
            ->add('fechaInicio', null, array(
                'label' => 'Fecha Inicial',
                'constraints' => array(
                    new NotBlank(array(
                        'message' => 'La fecha es obligatoria.'
                    ))
                ),
                'attr' => array(
                    'class' => 'fecha',
                    'placeholder' => 'Seleccione una fecha inicial',
                )
            ))
            ->add('fechaFin', null, array(
                'label' => 'Fecha Final',
                'constraints' => array(
                    new NotBlank(array(
                        'message' => 'La fecha es obligatoria.'
                    ))
                ),
                'attr' => array(
                    'class' => 'fecha',
                    'placeholder' => 'Seleccione una fecha final',
                )
            ))
            ->add('ciudad', EntityType::class, array(
                'label' => 'Ciudad',
                'class' => Ciudad::class,
                'constraints' => array(
                    new NotBlank(array(
                        'message' => 'La ciudad es obligatoria.'
                    ))
                ),
                'choice_label' => function ($ciudad) {
                    return $ciudad->getNombre() . '(' . $ciudad->getProvincia() . ')';
                },
                'placeholder' => 'Seleccione una ciudad',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.nombre', 'ASC')
                        ->join('c.coches', 'co');
                }
            ));

        $form = $form->getForm()->handleRequest($request);

        //Comprobamos si el formulario se ha enviado y es válido
        if ($form->isSubmitted() && $form->isValid()) {
            //Datos recibidos
            $data = $form->getData();

            //Guardamos el objeto alquiler con las fechas en sesión
            $alquiler = new Alquiler();
            $alquiler->setFechaInicio($data['fechaInicio']);
            $alquiler->setFechaFin($data['fechaFin']);
            $session->set('alquiler', $alquiler);

            return $this->redirectToRoute('coches', [
                'ciudad' => $data['ciudad']->getId()
            ]);
        }

        return $this->render('default/index.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/coches/{ciudad}", name="coches")
     */
    public function form2(Request $request, Ciudad $ciudad)
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        //Obtenemos la variable de sesión alquiler
        $session = $this->get("session");
        $alquiler = $session->get('alquiler');

        if (!$alquiler) {
            return $this->redirectToRoute('inicio');
        }

        //Segundo formulario
        $form = $this->createFormBuilder()
            ->add('coche', EntityType::class, array(
                'label' => 'Seleccione un coche',
                'class' => Coche::class,
                'constraints' => array(
                    new NotBlank(array(
                        'message' => 'El coche es obligatorio.'
                    ))
                ),
                'choice_label' => function ($coche) {
                    return ($coche->getPrecioDia() + 0) . '€/día - ' . $coche->getMarca() . ' ' . $coche->getModelo();
                },
                'expanded' => true
            ));

        $form = $form->getForm()->handleRequest($request);

        //Comprobamos si el formulario se ha enviado y es válido
        if ($form->isSubmitted() && $form->isValid()) {
            //Datos recibidos
            $data = $form->getData();

            //Guardamos el objeto alquiler con el coche en sesión
            $alquiler->setCoche($data['coche']);
            $session->set('alquiler', $alquiler);

            return $this->redirectToRoute('cliente');
        }

        return $this->render('default/coches.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/cliente", name="cliente")
     */
    public function form3(Request $request)
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        //Obtenemos la variable de sesión alquiler
        $session = $this->get("session");
        $alquiler = $session->get('alquiler');

        if (!$alquiler) {
            return $this->redirectToRoute('inicio');
        }

        $cliente = new Cliente();
        $em->persist($cliente);

        //Tercer formulario
        $formFactory = $this->get('form.factory');
        $form = $formFactory->createNamed('registro', ClienteType::class, $cliente);
        $form2 = $formFactory->createNamedBuilder('identificacion')
            ->add('dni', null, array(
                'label' => 'DNI',
                'constraints' => array(
                    new NotBlank(array(
                        'message' => 'El DNI es obligatorio.'
                    ))
                )
            ))->getForm();

        if ('POST' === $request->getMethod()) {

            //Comprobamos si el formulario de registro se ha enviado y es válido
            if ($request->request->has('registro')) {
                $form->handleRequest($request);

                if ($form->isSubmitted() && $form->isValid()) {
                    $fechaNacimiento = \DateTime::createFromFormat('d-m-Y', $cliente->getFechaNacimiento());

                    //Calculamos usando diff y la fecha actual
                    $calculo = $fechaNacimiento->diff(new \DateTime());

                    if ($calculo->y < 18)
                    {
                        $this->addFlash('error', 'Debe tener 18 años para poder alquilar');
                    }else {
                        $cliente->setFechaNacimiento($fechaNacimiento);

                        try {
                            $em->flush();

                            //Guardamos el objeto alquiler con el coche en sesión
                            $alquiler->setCliente($cliente);
                            $session->set('alquiler', $alquiler);

                            /*return $this->redirectToRoute('coches', [
                                'ciudad' => $data['ciudad']->getId()
                            ]);*/

                        } catch (UniqueConstraintViolationException $e) {
                            $this->addFlash('error', 'Ya existe un cliente con este DNI');
                        } catch (\Exception $e) {
                            $this->addFlash('error', 'No se ha podido registrar el nuevo cliente');
                        }
                    }
                }
            }

            //Comprobamos si el formulario de identificación se ha enviado y es válido
            if ($request->request->has('identificacion')) {
                $form2->handleRequest($request);

                if ($form2->isSubmitted() && $form2->isValid()) {
                    //Datos recibidos
                    $data = $form2->getData();

                    try {
                        $cliente = $em->createQueryBuilder()
                            ->select('c')
                            ->from('AppBundle:Cliente', 'c')
                            ->where('c.dni = :dni')
                            ->setParameter('dni', $data['dni'])
                            ->getQuery()
                            ->getSingleResult();

                        //Guardamos el objeto alquiler con el cliente en sesión
                        $alquiler->setCliente($cliente);
                        $session->set('alquiler', $alquiler);

                        /*return $this->redirectToRoute('coches', [
                            'ciudad' => $data['ciudad']->getId()
                        ]);*/
                    } catch (NoResultException $e) {
                        $this->addFlash('error', 'No existe ningún cliente con este DNI');
                    } catch (\Exception $e) {
                        $this->addFlash('error', 'No se ha podido encontrar el cliente');
                    }
                }
            }
        }

        return $this->render('default/cliente.html.twig', [
            'form' => $form->createView(),
            'form2' => $form2->createView(),
            'ciudad' => $alquiler->getCoche()->getCiudad()->getId()
        ]);
    }
}
