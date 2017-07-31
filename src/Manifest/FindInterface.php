<?php
namespace RefactorPhp\Manifest;

use PhpParser\Node;

interface FindInterface extends ManifestInterface
{
    public function getNodeCondition(Node $node);
}