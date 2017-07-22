<?php
namespace RefactorPhp\Manifest;

use PhpParser\Node;

interface FindInterface
{
    public function getNodeCondition(Node $node);
}