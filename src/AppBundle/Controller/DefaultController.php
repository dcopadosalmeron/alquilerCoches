<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Alquiler;
use AppBundle\Entity\Ciudad;
use AppBundle\Entity\Coche;
use Doctrine\ORM\EntityRepository;
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
        $session  = $this->get("session");
        $session->set('alquiler', null);

        //Primer formulario
        $form = $this->createFormBuilder()
            ->add('fechaInicio', null, array(
                'label' => 'Fecha Inicial',
                'constraints' => array(
                    new NotBlank(array(
                        'message'=>'La fecha es obligatoria.'
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
                        'message'=>'La fecha es obligatoria.'
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
                        'message'=>'La ciudad es obligatoria.'
                    ))
                ),
                'choice_label' => function ($ciudad) {
                    return $ciudad->getNombre().'('.$ciudad->getProvincia().')';
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
        $session  = $this->get("session");
        $alquiler = $session->get('alquiler');

        if(!$alquiler){
            return $this->redirectToRoute('inicio');
        }

        //Segundo formulario
        $form = $this->createFormBuilder()
            ->add('coche', EntityType::class, array(
                'label' => 'Seleccione un coche',
                'class' => Coche::class,
                'constraints' => array(
                    new NotBlank(array(
                        'message'=>'El coche es obligatorio.'
                    ))
                ),
                'choice_label' => function ($coche) {
                    return ($coche->getPrecioDia()+ 0).'€/día - '.$coche->getMarca().' '.$coche->getModelo();
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
        }

        return $this->render('default/coches.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
