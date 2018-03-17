<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'autocomplete'  => 'on',
                    'placeholder'   => 'Nom utilisateur'
                )
            ))
            ->add('email', EmailType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'autocomplete'  => 'on',
                    'placeholder'   => 'Adresse email'
                )
            ))
            ->add('plainPassword', RepeatedType::class, array(
                'type'  => PasswordType::class,
                'first_options' => array('label' => "Mot de passe", 'attr' => array(
                    'class' => 'form-control',
                    'placeholder'   => 'Mot de passe'
                )),
                'second_options' => array('label' => "Repeter le mot de passe", 'attr' => array(
                    'class' => 'form-control',
                    'placeholder'   => 'Repeter le mot de passe'
                )),

            ))
            /*->add('password', PasswordType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder'   => 'Mot de passe'
                )
            ))*/
            ->add('isActive', CheckboxType::class, array(
                'attr'  => array(
                    'class' => 'custom-control-input'
                ),
                'required' => false,
            ))
            /*->add('termsAccepted', CheckboxType::class, array(
                'mapped'    => false,
                'constraints'   => new IsTrue(),

            ))*/
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'csrf_protection'   => true,
            'csrf_field_name'   => '_csrf_token',
            'csrf_token_id' => 'user_id'
        ]);
    }
}
