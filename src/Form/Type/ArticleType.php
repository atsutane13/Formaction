<?php

namespace WF3\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Validator\Constraints as Assert;




class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', TextType::class,array(
            'attr'=>array(
                'class'=> 'form-control',
                'placeholder'=>'entrez votre titre'
                )
            ));


        $builder->add('content', TextareaType::class,array(

            'attr'=>array(
                'class'=> 'form-control',
                'rows'=> '8',
                'placeholder'=>'entrez votre texte'
                )
        ));


        $builder->add('duree', NumberType::class,array(

            'attr'=>array(
                'class'=> 'form-control'
                )
        ));
        
        $builder->add('publi', ChoiceType::class, array(
            'choices'  => array(
                'choisissez un role' => null,
                'Publier' => 'publier',
                'Temporaire' => 'temporaire',
                'Archiver' => 'archiver'
            ),
            'constraints'=>array(
                new Assert\NotBlank()                
                )            
            ));

        $builder->add('categoryId', ChoiceType::class, array(
            'choices'  => array(
                'choisissez un role' => null,
                'Web' => '1',
                'Menuiserie' => '2',
                'Peinture' => '3'
            ),
            'constraints'=>array(
                new Assert\NotBlank()                
                )            
            ));

        $builder->add('Enregistrer', SubmitType::class,array(
            'attr'=>array(
                'class'=> 'btn btn-default'
                )
        ));
    }

    public function getName()
    {
        return 'article';
    }
}
