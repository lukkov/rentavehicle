<?php

declare(strict_types=1);

namespace App\DataFixtures\Car\Model;

use App\DataFixtures\Car\Brand\BrandFixtures;
use App\Entity\Car\Brand\Brand;
use App\Entity\Car\Model\Model;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ModelFixtures extends Fixture implements DependentFixtureInterface
{
    public const MODEL_BMW_118 = 'bmw_118_reference';
    public const MODEL_BMW_320 = 'bmw_320_reference';
    public const MODEL_BMW_520 = 'bmw_520_reference';
    public const MODEL_AUDI_A3 = 'audi_a3_reference';
    public const MODEL_AUDI_A4 = 'audi_a4_reference';
    public const MODEL_AUDI_Q3 = 'audi_q3_reference';
    public const MODEL_OPEL_ASTRA_H = 'opel_astra_h_reference';
    public const MODEL_OPEL_ASTRA_J = 'opel_astra_j_reference';
    public const MODEL_OPEL_INSIGNIA = 'opel_insignia_reference';
    public const MODEL_VOLKSWAGEN_GOLF_6 = 'volkswagen_golf_6_reference';
    public const MODEL_VOLKSWAGEN_GOLF_7 = 'volkswagen_golf_7_reference';
    public const MODEL_VOLKSWAGEN_PASSAT = 'volkswagen_passat_reference';
    public const MODEL_ALFA_ROMEO_147 = 'alfa_romeo_147_reference';
    public const MODEL_ALFA_ROMEO_159 = 'alfa_romeo_159_reference';
    public const MODEL_ALFA_ROMEO_GIULIETTA = 'alfa_romeo_giulietta_reference';
    public const MODEL_CHEVROLET_AVEO = 'chevrolet_aveo_reference';
    public const MODEL_CHEVROLET_CRUZE = 'chevrolet_cruze_reference';
    public const MODEL_DACIA_SANDERO = 'dacia_sandero_reference';
    public const MODEL_DACIA_LOGAN = 'dacia_logan_reference';
    public const MODEL_DACIA_DUSTER = 'dacia_duster_reference';
    public const MODEL_FIAT_STILO = 'fiat_stilo_reference';
    public const MODEL_FIAT_BRAVO = 'fiat_bravo_reference';
    public const MODEL_FIAT_PUNTO = 'fiat_punto_reference';
    public const MODEL_HONDA_ACCORD = 'honda_accord_reference';
    public const MODEL_HONDA_CIVIC = 'honda_civic_reference';
    public const MODEL_KIA_CEED = 'kia_ceed_reference';
    public const MODEL_KIA_SPORTAGE = 'kia_sportage_reference';
    public const MODEL_KIA_RIO = 'kia_rio_reference';
    public const MODEL_PEUGEOT_508 = 'peugeot_508_reference';
    public const MODEL_PEUGEOT_3008 = 'peugeot_3008_reference';
    public const MODEL_PEUGEOT_607 = 'peugeot_607_reference';
    public const MODEL_RENAULT_CAPTURE = 'renault_capture_reference';
    public const MODEL_RENAULT_LAGUNA = 'renault_laguna_reference';
    public const MODEL_RENAULT_KADJAR = 'renault_kadjar_reference';
    public const MODEL_SEAT_LEON = 'seat_leon_reference';
    public const MODEL_SEAT_CORDOBA = 'seat_cordoba_reference';
    public const MODEL_TOYOTA_RAV4 = 'toyota_rav4_reference';
    public const MODEL_TOYOTA_YARIS = 'toyota_yaris_reference';

    public function load(ObjectManager $manager): void
    {
        foreach ($this->provideModels() as [$brandReference, $name, $modelReference]) {
            /** @var Brand $brand */
            $brand = $this->getReference($brandReference);
            $model = new Model($brand, $name);
            $manager->persist($model);
            $this->addReference($modelReference, $model);
        }

        $manager->flush();
    }

    private function provideModels(): iterable
    {
        yield [BrandFixtures::BRAND_BMW, '118', self::MODEL_BMW_118];
        yield [BrandFixtures::BRAND_BMW, '320', self::MODEL_BMW_320];
        yield [BrandFixtures::BRAND_BMW, '520', self::MODEL_BMW_520];
        yield [BrandFixtures::BRAND_AUDI, 'A3', self::MODEL_AUDI_A3];
        yield [BrandFixtures::BRAND_AUDI, 'A4', self::MODEL_AUDI_A4];
        yield [BrandFixtures::BRAND_AUDI, 'Q3', self::MODEL_AUDI_Q3];
        yield [BrandFixtures::BRAND_OPEL, 'Astra H', self::MODEL_OPEL_ASTRA_H];
        yield [BrandFixtures::BRAND_OPEL, 'Astra J', self::MODEL_OPEL_ASTRA_J];
        yield [BrandFixtures::BRAND_OPEL, 'Insignia', self::MODEL_OPEL_INSIGNIA];
        yield [BrandFixtures::BRAND_VOLKSWAGEN, 'Golf 6', self::MODEL_VOLKSWAGEN_GOLF_6];
        yield [BrandFixtures::BRAND_VOLKSWAGEN, 'Golf 7', self::MODEL_VOLKSWAGEN_GOLF_7];
        yield [BrandFixtures::BRAND_VOLKSWAGEN, 'Passat', self::MODEL_VOLKSWAGEN_PASSAT];
        yield [BrandFixtures::BRAND_ALFA_ROMEO, '147', self::MODEL_ALFA_ROMEO_147];
        yield [BrandFixtures::BRAND_ALFA_ROMEO, '159', self::MODEL_ALFA_ROMEO_159];
        yield [BrandFixtures::BRAND_ALFA_ROMEO, 'Giulietta', self::MODEL_ALFA_ROMEO_GIULIETTA];
        yield [BrandFixtures::BRAND_CHEVROLET, 'Aveo', self::MODEL_CHEVROLET_AVEO];
        yield [BrandFixtures::BRAND_CHEVROLET, 'Cruze', self::MODEL_CHEVROLET_CRUZE];
        yield [BrandFixtures::BRAND_DACIA, 'Sandero', self::MODEL_DACIA_SANDERO];
        yield [BrandFixtures::BRAND_DACIA, 'Logan', self::MODEL_DACIA_LOGAN];
        yield [BrandFixtures::BRAND_DACIA, 'Duster', self::MODEL_DACIA_DUSTER];
        yield [BrandFixtures::BRAND_FIAT, 'Stilo', self::MODEL_FIAT_STILO];
        yield [BrandFixtures::BRAND_FIAT, 'Bravo', self::MODEL_FIAT_BRAVO];
        yield [BrandFixtures::BRAND_FIAT, 'Punto', self::MODEL_FIAT_PUNTO];
        yield [BrandFixtures::BRAND_HONDA, 'Civic', self::MODEL_HONDA_CIVIC];
        yield [BrandFixtures::BRAND_HONDA, 'Accord', self::MODEL_HONDA_ACCORD];
        yield [BrandFixtures::BRAND_KIA, 'Ceed', self::MODEL_KIA_CEED];
        yield [BrandFixtures::BRAND_KIA, 'Sportage', self::MODEL_KIA_SPORTAGE];
        yield [BrandFixtures::BRAND_KIA, 'Rio', self::MODEL_KIA_RIO];
        yield [BrandFixtures::BRAND_PEUGEOT, '508', self::MODEL_PEUGEOT_508];
        yield [BrandFixtures::BRAND_PEUGEOT, '607', self::MODEL_PEUGEOT_607];
        yield [BrandFixtures::BRAND_PEUGEOT, '3008', self::MODEL_PEUGEOT_3008];
        yield [BrandFixtures::BRAND_RENAULT, 'Capture', self::MODEL_RENAULT_CAPTURE];
        yield [BrandFixtures::BRAND_RENAULT, 'Laguna', self::MODEL_RENAULT_LAGUNA];
        yield [BrandFixtures::BRAND_RENAULT, 'Kadjar', self::MODEL_RENAULT_KADJAR];
        yield [BrandFixtures::BRAND_SEAT, 'Leon', self::MODEL_SEAT_LEON];
        yield [BrandFixtures::BRAND_SEAT, 'Cordoba', self::MODEL_SEAT_CORDOBA];
        yield [BrandFixtures::BRAND_TOYOTA, 'Rav4', self::MODEL_TOYOTA_RAV4];
        yield [BrandFixtures::BRAND_TOYOTA, 'Yaris', self::MODEL_TOYOTA_YARIS];
    }

    public function getDependencies(): iterable
    {
        return [
            BrandFixtures::class,
        ];
    }
}