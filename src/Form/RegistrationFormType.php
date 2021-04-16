<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'label'=> false,
                'required' => true,
                'attr'=>[
                    'class'=>'form-control',
                    'pattern'=>"^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$"
                ]
            ])
            ->add('name', TextType::class, [
                'label'=> false,
                'required' => true,
                'attr'=>[
                    'class'=>'form-control',
                ]
            ])
            ->add('phone', TextType::class, [
                'label'=> false,
                'required' => true,
                'attr'=>[
                    'class'=>'form-control',
                ]
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'The password fields must match.',
                'required' => true,
                'first_options'  => [],
                'second_options' => [],
                'options' => [
                    'label' => false,
                    'attr' => [
                        'class' => 'form-control',
                        'oninput'=>"check(this)"
                    ]],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Register',
                'attr'=>['class'=>'btn btn-lg btn-primary']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
