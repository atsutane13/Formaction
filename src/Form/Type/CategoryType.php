<?php

namespace WF3\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints as Assert;




class CategoryType extends AbstractType
{
   public function buildForm(FormBuilderInterface $builder, array $options)
   {
       
       $builder->add('category', TextType::class,array(
           'attr'=>array (
               'class'=>'form-control',
               'placeholder'=> 'entrez une category'
           )
       ));

       $builder->add('image', FileType::class,
               array(
                   'required'=> false,
                   'constraints' => new Assert\Image()
                   
               )
        );

       $builder->add('Enregistrer', SubmitType::class,array(
           'attr'=>array (
               'class'=> 'btn btn-default'
           )
       ));
   }

   public function getName()
   {
       return 'category';
   }
}