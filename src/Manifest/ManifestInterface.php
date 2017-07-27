<?php
namespace RefactorPhp\Manifest;

use RefactorPhp\Finder;

/**
 * Interface ManifestInterface
 * @package RefactorPhp\Manifest
 */
interface ManifestInterface
{
    /**
     * @return Finder
     */
    public function getFinder(): Finder;
}