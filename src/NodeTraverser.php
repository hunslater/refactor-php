<?php
namespace RefactorPhp;

use PhpParser\NodeTraverser as BaseNodeTraverser;

class NodeTraverser extends BaseNodeTraverser
{
    public function traverse(array $nodes)
    {
        parent::traverse($nodes);
    }

}