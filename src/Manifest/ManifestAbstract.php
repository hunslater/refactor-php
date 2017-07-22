<?php
namespace RefactorPhp\Manifest;

/**
 * Class ManifestAbstract
 * @package RefactorPhp\Manifest
 */
abstract class ManifestAbstract implements ManifestInterface
{
    /**
     * @var
     */
    protected $action;
    /**
     * @var
     */
    protected $sourcePath;
    /**
     * @var
     */
    protected $outputPath;

    /**
     * {@inheritdoc}
     */
    public function getAction(): string
    {
        return $this->action;
    }

    /**
     * {@inheritdoc}
     */
    public function getSourcePath(): string
    {
        return $this->sourcePath;
    }

    /**
     * {@inheritdoc}
     */
    public function getOutputPath(): string
    {
        return $this->outputPath;
    }

}