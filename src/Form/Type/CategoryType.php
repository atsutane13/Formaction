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
               'class'=>'formulaire',
               'placeholder'=> 'entrez une category'
           )
       ));

       $builder->add('image', FileType::class,array(
                'required'=> false,
                'constraints' => array(
                    new Assert\Image(array(
                        'minWidth' => 200,
                        'maxWidth' => 400,
                        'minHeight' => 200,
                        'maxHeight' => 400,
                    )),
                    new Assert\File(array(
                        'maxSize' => '1024k')
                  )
                ),
                'attr'=>array(
                    'class'=> 'formulaire'
                )
            ));

       $builder->add('Enregistrer', SubmitType::class,array(
           'attr'=>array (
               'class'=> 'form-btn'
           )
       ));
   }

   public function getName()
   {
       return 'category';
   }
}