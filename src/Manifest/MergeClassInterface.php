<?php
namespace RefactorPhp\Manifest;

interface MergeClassInterface extends ManifestInterface
{
    public function getClassMap(): array;
}