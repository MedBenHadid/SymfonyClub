<?php

namespace CLubBundle\Form;

use CLubBundle\Entity\Club;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FormationType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('Titre')->add('description')->add('datedebut')->add('datefin')->add('nbrParticipant')->add('club',EntityType::class,['class'=>Club::class,'query_builder'=>function(EntityRepository $em){
            return $em->createQueryBuilder('u')->orderBy('u.nom');
        },'choice_label'=>'nom'])->add('Ajouter',SubmitType::class);
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CLubBundle\Entity\Formation'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'clubbundle_formation';
    }


}
