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
                'class'=> 'form-control',
                'placeholder'=>'entrez votre titre'
            ),
            'label'=>'Nom de la formation'
        ));

        $builder->add('duree', TextType::class,array(

            'attr'=>array(
                'class'=> 'form-control'
                )
        ));
        
        $builder->add('categoryId', ChoiceType::class, array(
            'choices'  => array(
                'choisissez un role' => null,
                'Web' => '1',
                'Metiers du bois' => '2',
                'Metiers de bouche' => '3'
            ),
            'constraints'=>array(
                new Assert\NotBlank()                
            ),
            'label'=>'categorie'
        ));

        $builder->add('intervenantId', ChoiceType::class, array(
            'choices'  => array(
                'choisissez un role' => null,
                'PHILOMATHIQUE BORDEAUX' => '1',
                'CFA' => '2',
                'LE WAGON' => '3',
                'WILD CODE SCHOOL BORDEAUX' => '4',
                'GRETA AQUITAINE' => '5',
                'MODULA BORDEAUX' => '6',
                'CESI EXIA BORDEAUX' => '7',
                'DIGITAL CAMPUS' => '8',
                'GIP FCIP AQUITAIN' => '9',
                'CFA: INSTITUT DES SAVEURS' => '10'
            ),
            'constraints'=>array(
                new Assert\NotBlank()                
            ),
            'label'=>'Intervenant'
        ));

        $builder->add('url', UrlType::class,array(
            'attr'=>array(
                'class'=> 'form-control',
                'placeholder'=>'entrez l\'url'
            ),
            'label'=>'url de la formation'
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
