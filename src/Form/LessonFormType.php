<?php


namespace App\Form;


use App\Entity\Course;
use App\Entity\Lesson;
use phpDocumentor\Reflection\Types\Collection;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LessonFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label'=> false,
                'required' => true,
                'attr'=>[
                    'class'=>'form-control',
                    'placeholder' => 'Название урока',

                ]
            ])
            ->add('description', TextType::class, [
                'label'=> false,
                'required' => true,
                'attr'=>[
                    'class'=>'form-control',
                    'placeholder' => 'Описание',
                ]
            ])
            ->add('video_link', FileType::class, [
                'label'=> false,
                'required' => true,
                'attr'=>[
                    'class'=>'form-control',
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Сохранить',
                'attr'=>['class'=>'btn btn-lg btn-primary']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Lesson::class,
            'allow_extra_fields' => true,
        ]);
    }
}