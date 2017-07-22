<?php
namespace RefactorPhp\Manifest;

final class ManifestReader
{
    /**
     * @var ManifestInterface
     */
    private $manifest;

    /**
     * ManifestReader constructor.
     * @param ManifestInterface $manifest
     */
    public function __construct(ManifestInterface $manifest)
    {
        $this->manifest = $manifest;
    }
}