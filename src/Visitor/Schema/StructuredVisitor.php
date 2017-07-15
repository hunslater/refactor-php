<?php
namespace RefactorPhp\Visitor\Schema;

use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;

class StructuredVisitor extends NodeVisitorAbstract
{
    /**
     * @var array
     */
    protected $stack;
    /**
     * @var Node
     */
    protected $prev;

    /**
     * @param array $nodes
     *
     * @return null|Node[] Array of nodes
     */
    public function beforeTraverse(array $nodes)
    {
        $this->stack = [];
        $this->prev = null;

        return $nodes;
    }

    /**
     * @param Node $node
     * @return Node
     */
    public function enterNode(Node $node)
    {
        if (!empty($this->stack)) {
            $node->setAttribute('parent', $this->stack[count($this->stack)-1]);
        }
        if ($this->prev && $this->prev->getAttribute('parent') == $node->getAttribute('parent')) {
            $node->setAttribute('prev', $this->prev);
            $this->prev->setAttribute('next', $node);
        }
        $this->stack[] = $node;

        return $node;
    }

    /**
     * @param Node $node
     *
     * @return array|mixed|null|Node[]
     */
    public function leaveNode(Node $node)
    {
        $this->prev = $node;
        array_pop($this->stack);

        return $node;
    }

}
