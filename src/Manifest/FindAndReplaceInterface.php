<?php
namespace RefactorPhp\Manifest;

use PhpParser\Node;

interface FindAndReplaceInterface extends ManifestInterface
{
    public function readNode(Node $node): Node;
}