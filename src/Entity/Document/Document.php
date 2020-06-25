<?php

declare(strict_types=1);

namespace App\Entity\Document;

use App\Entity\Model\IdTrait;
use App\Entity\Model\Timestampable\TimestampableInterface;
use App\Entity\Model\Timestampable\TimestampableTrait;
Use App\Repository\Document\DocumentRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Sluggable\Util\Urlizer;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @ORM\Entity(repositoryClass=DocumentRepository::class)
 * @ORM\Table(name="document")
 * @ORM\HasLifecycleCallbacks()
 */
class Document implements TimestampableInterface
{
    use IdTrait, TimestampableTrait {
        IdTrait::__construct as initId;
        TimestampableTrait::__construct as initTimestamps;
    }

    /**
     * @ORM\Column(type="string")
     */
    private string $name;

    public function __construct(File $file)
    {
        $this->name = $this->upload($file);
        $this->initId();
        $this->initTimestamps();
    }

    public function getName(): string
    {
        return $this->name;
    }

    protected function upload(File $file): string
    {
        $newName = Urlizer::urlize(pathinfo($this->processName($file), PATHINFO_FILENAME)) . '-' . uniqid() . '.' . $file->guessExtension();
        $file instanceof UploadedFile ? $this->moveFile($file, $newName) : $this->copyFile($file, $newName);

        return $newName;
    }

    private function processName(File $file): string
    {
        if ($file instanceof UploadedFile) {

            return $file->getClientOriginalName();
        }

        return $file->getFilename();
    }

    private function moveFile(File $uploadedFile, string $newFilename): void
    {
        $uploadedFile->move($this->getUploadRootDir(), $newFilename);
    }

    private function copyFile(File $file, string $newFilename): void
    {
        $newLocation = $this->getUploadRootDir(). $newFilename;
        $copied = copy($file->getPathname(), $newLocation);
        if (!$copied) {
            throw new FileException(sprintf('Could not move the file "%s" to "%s".', $file->getPathname(), $newLocation));
        }

        @chmod($newLocation, 0666 & ~umask());
    }

    protected function getUploadRootDir()
    {
        return __DIR__.'/../../../public/uploads/';
    }
}