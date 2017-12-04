<?php

namespace WF3\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class SearchType extends AbstractType{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('text', TextType::class,array(
            'attr'=>array(
                'class'=> 'form-control',
                'placeholder'=>'entrez un texte'
            )
        ));

        $builder->add('Recherche', SubmitType::class,array(
            'attr'=>array(
                'class'=> 'btn btn-default action'
                )
        ));
    }

    public function getName()
    {
        return 'article';
    }


}