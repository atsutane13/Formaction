<?php

namespace WF3\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;


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
        $builder->add('content', NumberType::class,array(
            'attr'=>array(
                'class'=> 'form-control',
                'rows'=> '8',
                'placeholder'=>'entrez votre texte'
                )
        ));

        $builder->add('duree', TextareaType::class,array(
            'attr'=>array(
                'class'=> 'form-control'
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
