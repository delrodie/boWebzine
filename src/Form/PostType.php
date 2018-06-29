<?php

namespace App\Form;

use App\Entity\Post;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre', TextType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'autocomplete'  => 'off',
                    'placeholder'   => 'Titre de l\'article'
                )
            ))
            ->add('contenu', TextareaType::class)
            ->add('tags', TextType::class, array(
                'attr' => array(
                    'class' => 'form-control tag-input',
                    'placeholder'   => 'Les mots clés',
                    'data-role' => "tagsinput",
                    'style' => 'color: #666'
                )
            ))
            ->add('statut', CheckboxType::class, array(
                'attr'  => array(
                    'class' => 'custom-control-input'
                ),
                'required' => false,
            ))
            ->add('imageFile', VichImageType::class, [
                'required' => false,
                'allow_delete' => true,
                'download_label' => 'Telecharger l\'illustration',
            ])
            ->add('categorie', null, array(
                'attr' => array(
                    'class' => 'form-control select-categorie',
                )
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
            'csrf_protection'   => true,
            'csrf_field_name'   => '_csrf_token',
            'csrf_token_id' => 'post_id'
        ]);
    }
}
