<?php

namespace WF3\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Regex;


class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('username', TextType::class,array(
            'attr'=>array(
                'class'=>'form-control',
                'placeholder'=>'entrez un nom'                
            )
        ));

        $builder->add('email', EmailType::class,array(
            'attr'=>array(
                'class'=>'form-control',
                'placeholder'=>'entrez un email'
            ),
            'constraints'=>array(
                new Assert\Email
            )
        ));

        $builder->add('phone', TextType::class,array(
            'attr'=>array(
                'class'=>'form-control',
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

        $builder->add('role', ChoiceType::class, array(
            'choices'  => array(
                'choisissez un role' => null,
                'User' => 'ROLE_USER',
                'Admin' => 'ROLE_ADMIN'
            ),
            'constraints'=>array(
                new Assert\NotBlank()                
            )            
        ));
        $builder->add('password', RepeatedType::class, array(
            'type' => PasswordType::class,
            'invalid_message' => 'The password fields must match.',
            'options' => array('attr' =>array('class' => 'password-field form-control','placeholder'=>'entrez un mot de passe')),
            'required' => true,
            'first_options'  => array('label' => 'Password'),
            'second_options' => array('label' => 'Repeat Password'),
        ));
        $builder->add('register', SubmitType::class);
    }

    public function getName()
    {
        return 'user';
    }
}
