<?php

namespace WF3\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints as Assert;



class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class,array(
            'attr'=>array(
                'class'=> 'form-control',
                'placeholder'=> 'Entrez votre nom'
            ),
            'constraints'=>array(
                new Assert\NotBlank(),
                new Assert\Length(array(
                    'min'=>3,
                    'max'=>20,
                    'minMessage'=>'le nom doit faire au moins 3 caracteres'
                ))
            )
        ));
        $builder->add('email', TextType::class,array(
            'attr'=>array (
                'class'=>'form-control',
                'placeholder'=> 'entrez votre email'
            ),
            'constraints'=>new Assert\Email()
        ));
        $builder->add('subject', TextType::class,array(
            'attr'=>array (
                'class'=>'form-control',
                'placeholder'=> 'entrez le sujet'
            )
        ));
        $builder->add('message', TextareaType::class,array(
            'attr'=>array (
                'class'=>'form-control',
                'rows'=> '4'
            )
        ));
        $builder->add('Enregistrer', SubmitType::class,array(
            'attr'=>array (
                'class'=> 'btn btn-default'
            )
        ));
    }

    public function getName()
    {
        return 'contact';
    }
}
