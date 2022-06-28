<?php

declare(strict_types=1);

namespace App\Profile\Form;

use App\Profile\Entity\Profile;
use App\Security\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

use function array_combine;

class RegistrationType extends AbstractType
{
    public function __construct(private readonly UrlGeneratorInterface $router)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->setMethod(Request::METHOD_POST)
            ->setAction($this->router->generate('profile_registration_register'))
            ->add('firstName', TextType::class, [
                'required'    => true,
                'mapped'      => false,
                'constraints' => [
                    new NotBlank()
                ],
            ])
            ->add('lastName', TextType::class, [
                'required'    => true,
                'mapped'      => false,
                'constraints' => [
                    new NotBlank()
                ],
            ])
            ->add('gender', ChoiceType::class, [
                'required'    => true,
                'mapped'      => false,
                'choices'     => array_combine(Profile::GENDERS, Profile::GENDERS),
                'constraints' => [
                    new NotBlank(),
                    new Choice(Profile::GENDERS),
                ],
            ])
            ->add('email', EmailType::class, [
                'required'    => true,
            ])
            ->add('password', PasswordType::class, [
                'required'    => true,
                'constraints' => [
                    new Length(['min' => 8]),
                ],
            ])
            ->add('submit', SubmitType::class, ['label' => 'Registration'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
