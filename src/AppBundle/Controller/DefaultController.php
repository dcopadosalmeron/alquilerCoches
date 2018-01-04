<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Ciudad;
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
            ->add('roomGroups', EntityType::class, array(
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

        return $this->render('default/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
