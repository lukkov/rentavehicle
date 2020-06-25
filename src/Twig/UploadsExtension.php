<?php

declare(strict_types=1);

namespace App\Twig;

use Symfony\Component\Asset\Context\RequestStackContext;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class UploadsExtension extends AbstractExtension
{
    private RequestStackContext $requestStackContext;
    private string $uploadsPath;

    public function __construct(RequestStackContext $requestStackContext, string $uploadsPath)
    {
        $this->uploadsPath = $uploadsPath;
        $this->requestStackContext = $requestStackContext;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('upload_asset', [$this, 'processUploadAsset']),
        ];
    }

    public function processUploadAsset(string $path): string
    {
        return $this->requestStackContext->getBasePath().'/uploads/'. $path;
    }
}