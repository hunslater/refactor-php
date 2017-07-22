<?php
namespace RefactorPhp\Manifest;

/**
 * Interface ManifestInterface
 * @package RefactorPhp\Manifest
 */
interface ManifestInterface
{
    /**
     * Source directories for the code, if there are exclusions, prepend with "!".
     * @return string|array
     */
    public function getSourcePath();

    /**
     * If the resulting code needs to be written elsewhere, define it here.
     * @return string
     */
    public function getOutputPath(): string;
}