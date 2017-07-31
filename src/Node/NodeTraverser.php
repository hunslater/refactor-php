<?php
namespace RefactorPhp\Node;

use PhpParser\Node;
use PhpParser\NodeTraverser as BaseNodeTraverser;
use RefactorPhp\Manifest\FindAndReplaceInterface;
use RefactorPhp\Manifest\FindInterface;
use RefactorPhp\Manifest\ManifestInterface;

class NodeTraverser extends BaseNodeTraverser
{
    /**
     * @param array $nodes
     * @param ManifestInterface $manifest
     * @return bool
     */
    public function matchesManifest(array $nodes, ManifestInterface $manifest): bool
    {
        // TODO: Logic

        return true;
    }

    /**
     * @param Node[] $nodes
     * @return Node[]
     */
    public function traverse(array $nodes): array
    {
        $this->stopTraversal = false;
        $nodes = $this->traverseArray($nodes);

        return $nodes;
    }

    /**
     * @param Node $node
     * @return Node
     */
    protected function traverseNode(Node $node)
    {
        foreach ($node->getSubNodeNames() as $name) {
            $subNode =& $node->$name;

            if ($this->manifest instanceof FindAndReplaceInterface || $this->manifest instanceof FindInterface) {
                $this->manifest->getNodeCondition($node);
            }

            if (is_array($subNode)) {
                $subNode = $this->traverseArray($subNode);
                if ($this->stopTraversal) {
                    break;
                }
            } elseif ($subNode instanceof Node) {
                $traverseChildren = true;

                if ($traverseChildren) {
                    $subNode = $this->traverseNode($subNode);
                    if ($this->stopTraversal) {
                        break;
                    }
                }
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
                $traverseChildren = true;

                if ($traverseChildren) {
                    $node = $this->traverseNode($node);
                    if ($this->stopTraversal) {
                        break;
                    }
                }
            }
        }

        if (!empty($doNodes)) {
            while (list($i, $replace) = array_pop($doNodes)) {
                array_splice($nodes, $i, 1, $replace);
            }
        }

        return $nodes;
    }
}