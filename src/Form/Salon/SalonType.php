<?php

declare(strict_types=1);

namespace App\Form\Salon;

use App\Entity\City\City;
use App\Entity\Document\Document;
use App\Entity\Salon\Salon;
use App\Entity\User\User;
use App\Form\Document\DocumentType;
use App\Repository\City\CityRepository;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotNull;

class SalonType extends AbstractType
{
    private Security $security;
    private CityRepository $cityRepository;

    public function __construct(CityRepository $cityRepository, Security $security)
    {
        $this->security = $security;
        $this->cityRepository = $cityRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('name', TextType::class, [
            'get_value' => fn (Salon $salon) => $salon->getName(),
            'update_value' => fn (string $name, Salon $salon) => $salon->setName($name),
            'constraints' => [
                new NotNull(),
                new Length(['min' => 3, 'max' => 50]),
            ]
        ]);

        $builder->add('description', CKEditorType::class, [
            'get_value' => fn (Salon $salon) => $salon->getDescription(),
            'update_value' => fn (string $description, Salon $salon) => $salon->setDescription($description),
            'attr' => ['rows' => 25],
            'constraints' => [
                new NotNull(),
                new Length(['min' => 100, 'max' => 2000]),
            ]
        ]);

        $builder->add('email', EmailType::class, [
            'get_value' => fn (Salon $salon) => $salon->getEmail(),
            'update_value' => fn (string $email, Salon $salon) => $salon->setEmail($email),
            'constraints' => [
                new NotNull(),
                new Length(['max' => 30]),
                new Email(),
            ]
        ]);

        $builder->add('phoneNumber', TelType::class, [
            'get_value' => fn (Salon $salon) => $salon->getPhoneNumber(),
            'update_value' => fn (string $phoneNumber, Salon $salon) => $salon->setPhoneNumber($phoneNumber),
            'constraints' => [
                new NotNull(),
                new Length(['min' => 3, 'max' => 50]),
            ]
        ]);

        $builder->add('city', EntityType::class, [
            'placeholder' => '-- City --',
            'class' => City::class,
            'choices' => $this->cityRepository->findAll(),
            'choice_label' => fn (City $city) => $city->getName() .'-' . $city->getPostalCode(),
            'get_value' => fn (Salon $salon) => $salon->getCity(),
            'update_value' => fn (City $city, Salon $salon) => $salon->setCity($city),
        ]);

        $builder->add('address', TextType::class, [
            'get_value' => fn (Salon $salon) => $salon->getAddress(),
            'update_value' => fn (string $address, Salon $salon) => $salon->setAddress($address),
            'constraints' => [
                new NotNull(),
                new Length(['min' => 10, 'max' => 50]),
            ]
        ]);

        $builder->add('image', DocumentType::class, [
            'get_value'    => fn() => null,
            'update_value' => function (?Document $document, Salon $salon) {
                if ($document) {
                    $salon->setImage($document);
                }
            },
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        /** @var User $user */
        $user = $this->security->getUser();

        $resolver->setDefaults([
            'data_class' => Salon::class,
            'factory' => fn (string $name, string $description, Document $image, string $email, string $phoneNumber, City $city, string $address) => new Salon($user, $city, $image, $address, $name, $description, $email, $phoneNumber),
        ]);
    }

    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        /** @var Salon|null $car */
        $salon = $form->getData();
        $view->vars['filename'] = $salon ? $salon->getImageFilename() : null;
    }
}