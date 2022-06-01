<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'lastname',
                TextType::class,
                ['label' => 'Nom *'],
                ['required' => true]
            )
            ->add(
                'firstname',
                TextType::class,
                ['label' => 'Prénom *'],
                ['required' => true]
            )
            ->add(
                'email',
                EmailType::class,
                ['label' => 'Email *'],
                ['required' => true]
            )
            ->add(
                'phone',
                TelType::class,
                ['label' => 'Téléphone *'],
                ['required' => true]
            )
            ->add(
                'plainPassword',
                PasswordType::class,
                [ // instead of being set onto the object directly,
                    // this is read and encoded in the controller
                    'mapped' => false,
                    'attr' => ['autocomplete' => 'new-password'],
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Merci d\'entrer un mot de passe',
                        ]),
                        new Length([
                            'min' => 8,
                            'minMessage' => 'Votre mot de passe doit contenir {{ limit }} caractères',
                            // max length allowed by Symfony for security reasons
                            'max' => 4096,
                        ]),
                    ],
                ],
            )
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'label' => 'Acceptez les conditions',
                'constraints' => [
                    new IsTrue([
                        'message' => 'Acceptez les conditions',
                    ]),
                ],
            ])
            ->add(
                'save',
                SubmitType::class,
                ['label' => 'Valider'],
                ['attr' => ['class' => 'save']]
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
