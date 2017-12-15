<?php

namespace WF3\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Constraints as Assert;


class ArticleType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', TextType::class,array(
            'attr'=>array(
                'class'=> 'formulaire',
                'placeholder'=>'entrez votre titre'
            ),
            'label'=>'Nom de la formation'
        ));

        $builder->add('duree', TextType::class,array(

            'attr'=>array(
                'class'=> 'formulaire',
                'placeholder'=>'entrez la duree de la formation'
                )
        ));
        
        $builder->add('categoryId', ChoiceType::class, array(
            'choices'  => array(
            ),
            'choice_attr'=>array(
                'choice_class'=> 'formulaire round'
            ),
            'constraints'=>array(
                new Assert\NotBlank(),
                new Assert\NotNULL()                
            ),
            'choice_label'=>'Categorie'
        ));

        $builder->add('intervenantId', ChoiceType::class, array(
            'choices'  => array(
            ),
            'choice_attr'=>array(
                'choice_class'=> 'formulaire round'
            ),
            'constraints'=>array(
                new Assert\NotBlank(),
                new Assert\NotNULL()                
            ),
            'choice_label'=>'Intervenant'
        ));

        $builder->add('url', UrlType::class,array(
            'attr'=>array(
                'class'=> 'formulaire',
                'placeholder'=>'entrez l\'url'
            ),
            'label'=>'Url de la formation'
        ));

        $builder->add('Enregistrer', SubmitType::class,array(
            'attr'=>array(
                'class'=> 'form-btn'
            )
            
        ));
    }

    public function getName()
    {
        return 'formation';
    }
}
