<?php


namespace App\Form;


use App\Entity\User;
use App\Entity\UserRequest;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FeedBackFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label'=> false,
                'required' => true,
                'attr'=>[
                    'class'=>'form-control',
                    'placeholder' => 'Ваше имя',
                ]
            ])
            ->add('phone', TextType::class, [
                'label'=> false,
                'required' => true,
                'attr'=>[
                    'class'=>'form-control',
                    'placeholder' => 'Телефон',
                ]
            ])
            ->add('email', EmailType::class, [
                'label'=> false,
                'required' => true,
                'attr'=>[
                    'class'=>'form-control',
                    'placeholder' => 'Email',
                    'pattern'=>"^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$"
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Отправить',
                'attr'=>['class'=>'btn btn-lg btn-primary']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => UserRequest::class,
        ]);
    }
}