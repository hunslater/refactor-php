<?php
declare(strict_types=1);

namespace RefactorPhp\Node;

use PhpParser\Node;
use PhpParser\NodeTraverser as BaseNodeTraverser;
use RefactorPhp\Manifest\FindAndReplaceInterface;
use RefactorPhp\Manifest\FindInterface;
use RefactorPhp\Manifest\ManifestInterface;

class NodeTraverser extends BaseNodeTraverser
{
    /**
     * @var bool
     */
    protected $isMatch;

    /**
     * @var ManifestInterface
     */
    protected $manifest;

    /**
     * @var array
     */
    protected $stack;

    /**
     * @var Node
     */
    protected $prev;

    /**
     * Resets traverse properties.
     */
    private function reset()
    {
        $this->prev = null;
        $this->stack = [];
        $this->isMatch = false;
        $this->stopTraversal = false;
    }

    /**
     * @param array $nodes
     * @param ManifestInterface $manifest
     * @return bool
     */
    public function matchesManifest(array $nodes, ManifestInterface $manifest): bool
    {
        $this->reset();
        $this->manifest = $manifest;
        $this->traverseArray($nodes);

        return $this->isMatch;
    }

    /**
     * @param Node $node
     * @return Node
     */
    protected function traverseNode(Node $node)
    {
        foreach ($node->getSubNodeNames() as $name) {
            $subNode =& $node->$name;

            if (is_array($subNode)) {
                $subNode = $this->traverseArray($subNode);
                if ($this->stopTraversal || $this->isMatch) {
                    break;
                }
            } elseif ($subNode instanceof Node) {
                $this->createRelations($subNode);
                $subNode = $this->traverseNode($subNode);

                if ($this->stopTraversal || $this->isMatch) {
                    break;
                }

                $this->prev = $node;
                array_pop($this->stack);

                $this->isMatchingManifest($subNode);
            }
        }

        return $node;
    }

    /**
     * @param Node[] $nodes
     * @return Node[]
     */
    protected function traverseArray(array $nodes)
    {
        $doNodes = array();
        foreach ($nodes as $i => &$node) {
            if (is_array($node)) {
                $node = $this->traverseArray($node);
                if ($this->stopTraversal) {
                    break;
                }
            } elseif ($node instanceof Node) {
                $this->createRelations($node);
                $node = $this->traverseNode($node);

                if ($this->stopTraversal || $this->isMatch) {
                    break;
                }

                $this->prev = $node;
                array_pop($this->stack);

                $this->isMatchingManifest($node);
            }
        }

        if (!empty($doNodes)) {
            while (list($i, $replace) = array_pop($doNodes)) {
                array_splice($nodes, $i, 1, $replace);
            }
        }

        return $nodes;
    }

    /**
     * @param Node $node
     */
    protected function createRelations(Node $node)
    {
        if (!empty($this->stack)) {
            $node->setAttribute('parent', $this->stack[count($this->stack) - 1]);
        }
        if ($this->prev && $this->prev->getAttribute('parent') == $node->getAttribute('parent')) {
            $node->setAttribute('prev', $this->prev);
            $this->prev->setAttribute('next', $node);
        }
        $this->stack[] = $node;
    }

    /**
     * @param Node $node
     */
    protected function isMatchingManifest(Node $node)
    {
        if ($this->manifest instanceof FindAndReplaceInterface || $this->manifest instanceof FindInterface) {
            if ($this->manifest->getNodeCondition($node)) {
                $this->isMatch = true;
            }
        }
    }
}