<?php
declare(strict_types=1);

namespace RefactorPhp\Manifest;

use ReflectionClass;

final class ManifestResolver
{
    /**
     * @var ManifestInterface
     */
    private $manifest;

    /**
     * ManifestResolver constructor.
     * @param ManifestInterface $manifest
     */
    public function __construct(ManifestInterface $manifest)
    {
        $this->manifest = $manifest;
    }

    /**
     * @return string
     */
    public function getInterface(): string
    {
        $reflection = new ReflectionClass($this->manifest);
        foreach ($reflection->getInterfaces() as $interface) {
            if (ManifestInterface::class !== $interface->getName()) {
                return $interface->getName();
            }
        }

        throw new \LogicException(
            "Provided manifest {$reflection->getName()} is not supported."
        );
    }
}