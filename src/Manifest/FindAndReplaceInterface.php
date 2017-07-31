<?php
namespace RefactorPhp\Manifest;

use PhpParser\Node;

interface FindAndReplaceInterface extends ManifestInterface
{
    public function getNodeCondition(Node $node);
    public function getNodeReplacement(Node $node): Node;
}