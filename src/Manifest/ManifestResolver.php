<?php
declare(strict_types=1);

namespace RefactorPhp\Manifest;

use RefactorPhp\Exception\NoFilesException;
use RefactorPhp\Finder;
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
            if (in_array(ManifestInterface::class, $interface->getInterfaceNames())) {
                return $interface->getName();
            }
        }

        throw new \LogicException(
            "Provided manifest {$reflection->getName()} is not supported."
        );
    }

    /**
     * @return Finder
     * @throws NoFilesException
     */
    public function getFinder()
    {
        $finder = $this->manifest->getFinder();
        if ($finder->count() === 0) {
            throw new NoFilesException("No valid files found by provided Finder condition");
        }

        return $finder;
    }
}