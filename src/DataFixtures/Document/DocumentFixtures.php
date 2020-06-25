<?php

declare(strict_types=1);

namespace App\DataFixtures\Document;

use App\Entity\Document\Document;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\File\File;

class DocumentFixtures extends Fixture
{
    public const DOCUMENT_CAR_OPEL_ASTRA_J = 'document_car_opel_astra_j_reference';
    public const DOCUMENT_CAR_RENAULT_CAPTURE = 'document_car_renault_capture_reference';
    public const DOCUMENT_SALON_MILOJE = 'document_salon_miloje_reference';
    public const DOCUMENT_SALON_DRAGISA = 'document_salon_dragisa_reference';

    public function load(ObjectManager $manager)
    {
        foreach ($this->provideDocuments() as [$path, $documentReference]) {
            $document = new Document(new File($path));
            $manager->persist($document);
            $this->addReference($documentReference, $document);
        }
        $manager->flush();
    }

    private function provideDocuments(): iterable
    {
        yield [__DIR__ . '/../_images/cars/opel_astra_j.jpg', self::DOCUMENT_CAR_OPEL_ASTRA_J];
        yield [__DIR__ . '/../_images/cars/renault_capture.jpg', self::DOCUMENT_CAR_RENAULT_CAPTURE];
        yield [__DIR__ . '/../_images/salons/salon_miloje.jpg', self::DOCUMENT_SALON_MILOJE];
        yield [__DIR__ . '/../_images/salons/salon_dragisa.jpg', self::DOCUMENT_SALON_DRAGISA];
    }
}