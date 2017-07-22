<?php
namespace RefactorPhp\Manifest;

use PhpParser\Node;

interface FindAndReplaceInterface
{
    public function getNodeCondition(Node $node);
    public function getNodeReplacement(Node $node);
}