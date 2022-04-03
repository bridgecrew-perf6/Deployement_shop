<?php

namespace App\Form;

use App\Entity\Address;
use App\Entity\Carrier;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class CommandeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        #je vais passer user et je lui demande options notemment mon utilisateur et a l'interieur j'ai mon object pour l'exploiter coté vu
        $user = $options['user'];

        $builder
            ->add('addresses', EntityType::class, [//afficher les adresse de type EntityType
                'label' => false,
                'required' => true,//on a besoin que l'utilisateur choissise une adresse
                'class' => Address::class,//on souhaite que la classe adresse fasse le lien avec ce formulaire
                'choices' => $user->getAddresses(),//reccuperer les adresse de l'utilisateur
                'multiple' => false,//on veut pas que le champs soit multible on veut des radio button
                'expanded' => true//on veut des radio button
            ])
            ->add('carriers', EntityType::class, [
                'label' => 'Choisissez votre transporteur',
                'required' => true,
                'class' => Carrier::class,
                'multiple' => false,
                'expanded' => true
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Valider ma commande',
                'attr' => [
                    'class' => 'btn btn-success btn-block'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            //je veux passer a mon formulaire user et lui dire que c'est un tableau vide pour le moment
            'user' => array()
        ]);
    }
}