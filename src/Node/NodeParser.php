<?php
declare(strict_types=1);

namespace RefactorPhp\Node;

use PhpParser\Node;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor;
use PhpParser\Parser;
use RefactorPhp\Manifest\FindAndReplaceInterface;
use RefactorPhp\Manifest\NodeManifestInterface;
use RefactorPhp\Visitor\ApplyManifestVisitor;
use RefactorPhp\Visitor\CreateNodeRelationshipVisitor;
use RefactorPhp\Visitor\MatchesManifestVisitor;
use Symfony\Component\Finder\SplFileInfo;

final class NodeParser implements NodeParserInterface
{
    /**
     * @var Parser
     */
    private $parser;

    /**
     * @var NodeTraverser
     */
    private $traverser;

    /**
     * NodeParser constructor.
     * @param Parser $parser
     * @param NodeTraverser $traverser
     */
    public function __construct(Parser $parser, NodeTraverser $traverser)
    {
        $this->parser = $parser;
        $this->traverser = $traverser;
    }

    /**
     * {@inheritdoc}
     */
    public function getFileNodes(SplFileInfo $file): array
    {
        return $this->traverseWithVisitor(
            $this->parser->parse($file->getContents()),
            new CreateNodeRelationshipVisitor()
        );
    }

    /**
     * {@inheritdoc}
     */
    public function matchesManifest(array $nodes, NodeManifestInterface $manifest): bool
    {
        $nodes = $this->traverseWithVisitor($nodes, new MatchesManifestVisitor($manifest));

        return $nodes === MatchesManifestVisitor::MANIFEST_MATCHED;
    }

    /**
     * {@inheritdoc}
     */
    public function applyManifest(array $nodes, FindAndReplaceInterface $manifest): array
    {
        return $this->traverseWithVisitor($nodes, new ApplyManifestVisitor($manifest));
    }

    /**
     * @param array $nodes
     * @param NodeVisitor $visitor
     * @return array|Node[]
     */
    private function traverseWithVisitor(array $nodes, NodeVisitor $visitor)
    {
        $this->traverser->addVisitor($visitor);
        $nodes = $this->traverser->traverse($nodes);
        $this->traverser->removeVisitor($visitor);

        return $nodes;
    }
}