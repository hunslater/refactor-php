<?php
declare(strict_types=1);

namespace RefactorPhp\Manifest;

use RefactorPhp\Exception\InvalidProcessorException;
use RefactorPhp\Processor\FindAndReplaceProcessor;
use RefactorPhp\Processor\FindProcessor;
use ReflectionClass;

final class ManifestResolver
{
    /**
     * Processor map.
     */
    const PROCESSORS = [
        FindInterface::class            => FindProcessor::class,
        FindAndReplaceInterface::class  => FindAndReplaceProcessor::class,
    ];

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
     * @throws InvalidProcessorException
     */
    public function getProcessorClass(): string
    {
        $reflection = new ReflectionClass($this->manifest);
        foreach ($reflection->getInterfaces() as $interface) {
            if (ManifestInterface::class !== ($name = $interface->getName())
                && array_key_exists($name, self::PROCESSORS)
            ) {
                return self::PROCESSORS[$name];
            }
        }

        throw new InvalidProcessorException(
            "Unable to initialise processor for manifest {$reflection->getName()}."
        );
    }

}