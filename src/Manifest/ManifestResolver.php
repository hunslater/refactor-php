<?php
declare(strict_types=1);

namespace RefactorPhp\Manifest;

use LogicException;
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
     * @var string
     */
    private $manifestInterface;

    /**
     * ManifestResolver constructor.
     * @param ManifestInterface $manifest
     */
    public function __construct(ManifestInterface $manifest)
    {
        $this->manifest = $manifest;
        $this->validate();
    }

    /**
     * Validates provided manifest.
     */
    public function validate()
    {
        $reflection = new ReflectionClass($this->manifest);
        $this->validateInterface($reflection);
    }

    /**
     * @param string $manifestInterface
     */
    public function setManifestInterface(string $manifestInterface)
    {
        $this->manifestInterface = $manifestInterface;
    }

    /**
     * @return string
     */
    public function getManifestInterface(): string
    {
        return $this->manifestInterface;
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

    /**
     * @return ManifestInterface|FindInterface|FindAndReplaceInterface|FileMergeInterface|FileSplitInterface
     */
    public function getManifest(): ManifestInterface
    {
        return $this->manifest;
    }

    /**
     * @param ReflectionClass $reflection
     */
    private function validateInterface(ReflectionClass $reflection)
    {
        foreach ($reflection->getInterfaces() as $interface) {
            if (in_array(ManifestInterface::class, $interface->getInterfaceNames())) {
                $this->setManifestInterface($interface->getName());
                break;
            } elseif ($interface->getName() === ManifestInterface::class) {
                throw new LogicException(
                    "Cannot use '{$interface->getName()}' directly, implement specific interfaces instead."
                );
            }
        }
    }
}