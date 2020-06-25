<?php

declare(strict_types=1);

namespace App\Form\Car;

use App\Repository\Car\Brand\BrandRepository;
use App\Repository\Car\CarRepository;
use App\Repository\Car\Feature\FeatureRepository;
use App\Repository\Car\Model\ModelRepository;
use App\Repository\Salon\SalonRepository;
use Symfony\Component\Security\Core\Security;

class ProfileCarType extends AbstractCarType
{
    protected SalonRepository $salonRepository;

    public function __construct(
        CarRepository $carRepository,
        SalonRepository $salonRepository,
        ModelRepository $modelRepository,
        FeatureRepository $featureRepository,
        BrandRepository $brandRepository,
        Security $security
    )
    {
        $this->salonRepository = $salonRepository;
        parent::__construct($carRepository, $modelRepository, $featureRepository, $brandRepository, $security);
    }

    protected function getSalons(): iterable
    {
        return $this->salonRepository->findByOwnerQueryBuilder($this->security->getUser())->getQuery()->getResult();
    }
}