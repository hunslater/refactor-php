<?php
namespace RefactorPhp\Processor;

use PhpParser\NodeVisitor;
use Symfony\Component\Finder\SplFileInfo;

interface FileProcessorInterface
{
    public function addVisitor(NodeVisitor $visitor);
    public function addVisitors(array $visitors);

    public function setFindPattern(string $findPattern);
    public function getFindPattern(): string;

    public function getExcludedDirs(): array;
    public function setExcludedDirs(array $excludedDirs);

    public function processFile(SplFileInfo $file);
}