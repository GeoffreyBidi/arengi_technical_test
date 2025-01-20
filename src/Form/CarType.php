<?php

namespace App\Form;

use App\Entity\CarCategoryEnum;
use App\Form\DataTransformer\CarCategoryEnumToStringTransformer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Car;
use App\Entity\User;

class CarType extends AbstractType
{
    private CarCategoryEnumToStringTransformer $dataTransformer;

    public function __construct(CarCategoryEnumToStringTransformer $dataTransformer)
    {
        $this->dataTransformer = $dataTransformer;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('brand', TextType::class, [
                'label' => 'Marque',
            ])
            ->add('category', ChoiceType::class, [
                'choices' => array_combine(
                    array_map(static fn($enum) => $enum->name, CarCategoryEnum::cases()),
                    CarCategoryEnum::cases()
                ),
                'expanded' => false,
                'multiple' => false,
            ])
            ->add('color', TextType::class, [
                'label' => 'Couleur',
            ])
            ->add('seatNumber', IntegerType::class, [
                'label' => 'Nombre de sièges'
            ])
            ->add('maximumAllowedWeight', IntegerType::class, [
                'label' => 'PTRA'
            ])
            ->add('author', EntityType::class, [
                'class'        => User::class,
                'label'        => 'Author',
                'choice_label' => function (User $user) {
                    return $user->getEmail();
                }
            ])
        ;

        $builder->get('category')->addModelTransformer($this->dataTransformer);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => Car::class]);
    }
}
