<?php
namespace RefactorPhp\Manifest;

abstract class ManifestAbstract implements ManifestInterface
{
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