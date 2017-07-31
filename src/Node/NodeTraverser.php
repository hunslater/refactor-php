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
     * @param array $nodes
     * @param ManifestInterface $manifest
     * @return bool
     */
    public function matchesManifest(array $nodes, ManifestInterface $manifest): bool
    {
        $this->isMatch = false;
        $this->manifest = $manifest;
        $this->traverseArray($nodes);

        return $this->isMatch;
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

                if ($this->manifest instanceof FindAndReplaceInterface || $this->manifest instanceof FindInterface) {
                    if ($this->manifest->getNodeCondition($node)) {
                        $this->isMatch = true;
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

                if ($this->manifest instanceof FindAndReplaceInterface || $this->manifest instanceof FindInterface) {
                    if ($this->manifest->getNodeCondition($node)) {
                        $this->isMatch = true;
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