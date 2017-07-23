<?php
namespace RefactorPhp\Manifest;

use PhpParser\Node;

interface FindAndReplaceInterface
{
    public function readNode(Node $node): Node;
}