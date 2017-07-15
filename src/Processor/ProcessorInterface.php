<?php
namespace RefactorPhp\Processor;

interface ProcessorInterface
{
    public function getSourceDir(): string;
    public function getOutputDir(): string;
    public function setSourceDir(string $sourceDir);
    public function setOutputDir(string $outputDir);
}