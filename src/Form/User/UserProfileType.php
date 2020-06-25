<?php

declare(strict_types=1);

namespace App\Form\User;

use App\Entity\User\User;
use App\Validator\Password\PasswordConstraint;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotNull;

class UserProfileType extends AbstractType
{
    private PasswordEncoderInterface $encoder;

    public function __construct(EncoderFactoryInterface $encoderFactory)
    {
        $this->encoder = $encoderFactory->getEncoder(User::class);
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('firstName', TextType::class, [
            'get_value' => fn (User $user) => $user->getFirstName(),
            'update_value' => fn (string $firstName, User $user) => $user->setFirstName($firstName),
            'constraints' => [
                new NotNull(),
                new Length(['min' => 3, 'max' => 20]),
            ],
        ]);

        $builder->add('lastName', TextType::class, [
            'get_value' => fn (User $user) => $user->getLastName(),
            'update_value' => fn (string $lastName, User $user) => $user->setLastName($lastName),
            'constraints' => [
                new NotNull(),
                new Length(['min' => 3, 'max' => 20]),
            ],
        ]);

        $builder->add('email', EmailType::class, [
            'get_value' => fn (User $user) => $user->getEmail(),
            'update_value' => fn (string $email, User $user) => $user->setEmail($email),
            'constraints' => [
                new NotNull(),
                new Email(),
            ],
        ]);

        $builder->add('password', RepeatedType::class, [
            'type' => PasswordType::class,
            'invalid_message' => 'The password fields must match.',
            'first_options'  => ['label' => 'Password'],
            'second_options' => ['label' => 'Repeat Password'],
            'get_value' => fn (User $user) => $user->getPassword(),
            'update_value' => function (?string $password, User $user) {
                if ($password) {
                    $user->setPassword($this->encoder->encodePassword($password, null));
                }
            },
            'constraints' => [
                new PasswordConstraint(),
            ],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'factory' => fn (string $firstName, string $lastName, string $email, string $password) => new User($firstName, $lastName, $email, $this->encoder->encodePassword($password, null)),
            'constraints' => [new UniqueEntity(['fields' => 'email'])],
        ]);
    }
}