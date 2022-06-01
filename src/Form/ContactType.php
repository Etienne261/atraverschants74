<?php

namespace App\Form;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class, 
                ['label' => 'Prénom *'],
                ['required' => true]
                )
            ->add('lastname', TextType::class,
                ['label' => 'Nom *'],
                ['required' => true]
                )
            ->add('email', EmailType::class,
                ['label' => 'Email *'],
                ['required' => true]
                )
            ->add('phone', TelType::class,
                ['label' => 'Téléphone *'],
                ['required' => true]
                )
            ->add('message', TextareaType::class, [
                'attr' => ['rows' => 5] ],
                ['required' => true]
                )
            ->add('save', SubmitType::class,
                ['label' => 'Envoyer'],
                ['attr' => ['class' => 'save'] ]
                )
           
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
