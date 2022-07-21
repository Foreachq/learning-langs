<?php

declare(strict_types=1);

namespace App\Learning\Form;

use App\Learning\Entity\Word;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class WordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('english', TextType::class, [
                'required'    => true,
                'constraints' => [
                    new NotBlank()
                ],
            ])
            ->add('russian', TextType::class, [
                'required'    => true,
                'constraints' => [
                    new NotBlank()
                ],
            ])
            ->add('submit', SubmitType::class, ['label' => 'Submit'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Word::class,
            'empty_data' => function (FormInterface $form) {
                return new Word(
                    (string) $form->get('english')->getData(),
                    (string) $form->get('russian')->getData(),
                );
            },
        ]);
    }
}
