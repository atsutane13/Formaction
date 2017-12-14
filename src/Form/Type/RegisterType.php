<?php

namespace WF3\Form\Type;

use Symfony\Component\Form\AbstractType;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Regex;


class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('nom', TextType::class,array(
            'attr'=>array(
                'class'=>'formulaire',
                'placeholder'=>'entrez un nom'                
            )
        ));

        $builder->add('phone', TextType::class,array(
            'attr'=>array(
                'class'=>'formulaire',
                'placeholder'=>'entrez un numero de telephone'
            ),
            'constraints'=>array(
                new Assert\NotBlank(),
                new Regex(array(
                    'pattern'   => '/^0[0-9]([ -]?[0-9]{2}){4}$/',
                    'match'     => true
                ))
            )
        ));

        $builder->add('contact', UrlType::class,array(
            'attr'=>array(
                'class'=>'formulaire',
                'placeholder'=>'entrez un un URL de la page'
            ),
            'constraints'=>array(
                new Assert\Url()
            )
        ));

        $builder->add('logo', FileType::class,array(
            'attr'=>array(
                'class' => 'formulaire'
            ),
            'required'=> false,
            'constraints'=> new Assert\Image()
        ));

        $builder->add('register', SubmitType::class,array(
            'attr'=>array (
                'class'=> 'form'
            )
        ));
    }

    public function getName()
    {
        return 'intervenant';
    }
}
