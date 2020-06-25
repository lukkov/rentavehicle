<?php

declare(strict_types=1);

namespace App\Entity\Car;

use App\Entity\Car\Enum\CarTypeEnum;
use App\Entity\Car\Enum\ColorEnum;
use App\Entity\Car\Enum\FuelEnum;
use App\Entity\Car\Enum\NumberOfDoorsEnum;
use App\Entity\Car\Enum\NumberOfSeatsEnum;
use App\Entity\Car\Enum\TransmissionEnum;
use App\Entity\Car\Model\Model;
use App\Entity\Document\Document;
use App\Entity\Model\IdTrait;
use App\Entity\Model\Timestampable\TimestampableInterface;
use App\Entity\Model\Timestampable\TimestampableTrait;
use App\Entity\Salon\Salon;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Repository\Car\CarRepository;
use App\Entity\Car\Feature\Feature;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CarRepository::class)
 * @ORM\Table(name="car")
 * @ORM\HasLifecycleCallbacks()
 */
class Car implements TimestampableInterface
{
    use IdTrait, TimestampableTrait {
        IdTrait::__construct as initId;
        TimestampableTrait::__construct as initTimestamps;
    }

    /**
     * @ORM\ManyToOne(targetEntity=Salon::class)
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private Salon $salon;

    /**
     * @ORM\ManyToOne(targetEntity=Model::class)
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private Model $model;

    /**
     * @ORM\ManyToMany(targetEntity=Feature::class)
     */
    private Collection $features;

    /**
     * @ORM\Column(type="string")
     */
    private string $type;

    /**
     * @ORM\Column(type="string")
     */
    private string $color;

    /**
     * @ORM\Column(type="string")
     */
    private string $fuel;

    /**
     * @ORM\Column(type="string")
     */
    private string $transmission;

    /**
     * @ORM\Column(type="string")
     */
    private string $numberOfDoors;

    /**
     * @ORM\Column(type="string")
     */
    private string $numberOfSeats;

    /**
     * @ORM\Column(type="integer")
     */
    private int $pricePerDay;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $featured;

    /**
     * @ORM\OneToOne(targetEntity=Document::class, cascade={"PERSIST"}, fetch="EAGER")
     */
    private Document $image;

    public function __construct(Salon $salon, Model $model, Document $image, string $type, string $color, string $fuel, string $transmission, string $numberOfDoors, string $numberOfSeats, int $pricePerDay, bool $featured = false)
    {
        $this->salon = $salon;
        $this->model = $model;
        $this->image = $image;
        $this->type = $type;
        $this->color = $color;
        $this->fuel = $fuel;
        $this->transmission = $transmission;
        $this->numberOfDoors = $numberOfDoors;
        $this->numberOfSeats = $numberOfSeats;
        $this->pricePerDay = $pricePerDay;
        $this->featured = $featured;
        $this->features = new ArrayCollection();
        $this->initId();
        $this->initTimestamps();
    }

    public function getSalon(): Salon
    {
        return $this->salon;
    }

    public function setSalon(Salon $salon): void
    {
        $this->salon = $salon;
    }

    public function getModel(): Model
    {
        return $this->model;
    }

    public function setModel(Model $model): void
    {
        $this->model = $model;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function getColor(): string
    {
        return $this->color;
    }

    public function setColor(string $color): void
    {
        $this->color = $color;
    }

    public function getFuel(): string
    {
        return $this->fuel;
    }

    public function setFuel(string $fuel): void
    {
        $this->fuel = $fuel;
    }

    public function getTransmission(): string
    {
        return $this->transmission;
    }

    public function setTransmission(string $transmission): void
    {
        $this->transmission = $transmission;
    }

    public function getNumberOfDoors(): string
    {
        return $this->numberOfDoors;
    }

    public function setNumberOfDoors(string $numberOfDoors): void
    {
        $this->numberOfDoors = $numberOfDoors;
    }

    public function getNumberOfSeats(): string
    {
        return $this->numberOfSeats;
    }

    public function setNumberOfSeats(string $numberOfSeats): void
    {
        $this->numberOfSeats = $numberOfSeats;
    }

    /** @return Feature[] */
    public function getFeatures(): array
    {
        return $this->features->toArray();
    }

    public function addFeature(Feature $feature): void
    {
        if (!$this->features->contains($feature)) {
            $this->features->add($feature);
        }
    }

    public function removeFeature(Feature $feature): void
    {
        $this->features->removeElement($feature);
    }

    public function getPricePerDay(): int
    {
        return $this->pricePerDay;
    }

    public function setPricePerDay(int $pricePerDay): void
    {
        $this->pricePerDay = $pricePerDay;
    }

    public function isFeatured(): bool
    {
        return $this->featured;
    }

    public function setFeatured(bool $featured): void
    {
        $this->featured = $featured;
    }

    public function getImageFilename(): ?string
    {
        $image = $this->getImage();

        return $image ? $image->getName() : null;
    }

    public function getImage(): Document
    {
        return $this->image;
    }

    public function setImage(Document $image): void
    {
        $this->image = $image;
    }

    public function getEnumValue($what): string
    {
        $map = [
            'type' => fn() => CarTypeEnum::getViewFormat($this->type),
            'fuel' => fn() => FuelEnum::getViewFormat($this->fuel),
            'transmission' => fn() => TransmissionEnum::getViewFormat($this->transmission),
            'number-of-doors' => fn() => NumberOfDoorsEnum::getViewFormat($this->numberOfDoors),
            'number-of-seats' => fn() => NumberOfSeatsEnum::getViewFormat($this->numberOfSeats),
            'color' => fn() => ColorEnum::getViewFormat($this->color),
        ];

        return $map[$what]();
    }
}
