<?php
namespace RefactorPhp\Manifest;

/**
 * Interface ManifestInterface
 * @package RefactorPhp\Manifest
 */
interface ManifestInterface
{
    const ACTION_FIND = 1;
    const ACTION_FIND_REPLACE = 2;
    const ACTION_FILE_MERGE = 3;
    const ACTION_FILE_SPLIT = 4;
    /**
     * Action that will be performed for this manifest.
     * @return string
     */
    public function getAction(): string;

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