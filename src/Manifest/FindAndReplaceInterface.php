<?php
namespace RefactorPhp\Manifest;

use PhpParser\Node;

interface FindAndReplaceInterface extends NodeManifestInterface
{
    public function getNodeReplacement(Node $node): Node;
}