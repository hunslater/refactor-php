<?php
declare(strict_types=1);

namespace RefactorPhp\Node;

use PhpParser\Node;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor;
use PhpParser\Parser;
use RefactorPhp\ClassDescription;
use RefactorPhp\Manifest\FindAndReplaceInterface;
use RefactorPhp\Manifest\NodeManifestInterface;
use RefactorPhp\Visitor\ApplyManifestVisitor;
use RefactorPhp\Visitor\ClassDescriptionVisitor;
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
    public function getFileNodes(SplFileInfo $file, bool $withRelationships = true): array
    {
        if ($withRelationships) {
            $nodes = $this->traverseWithVisitor(
                $this->parser->parse($file->getContents()),
                new CreateNodeRelationshipVisitor()
            );
        } else {
            $nodes = $this->parser->parse($file->getContents());
        }

        return $nodes;
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

    /**
     * @param string $filename
     * @return array
     */
    protected function getClassNodes(string $filename): array
    {
        $file = new SplFileInfo($filename, $filename, $filename);
        $nodes = $this->getFileNodes($file, false);

        if (count($nodes) !== 1) {
            throw new \LogicException(sprintf(
                "Provided file %s contains non-class definitions.",
                basename($filename)
            ));
        }

        if ( ! $nodes[0] instanceof Node\Stmt\Class_) {
            throw new \LogicException(sprintf(
                "Unable to find class definition in %s.",
                basename($filename)
            ));
        }

        return $nodes;
    }

    /**
     * @param string $filename
     * @return ClassDescription
     */
    public function getClassDescription(string $filename): ClassDescription
    {
        $nodes = $this->getClassNodes($filename);
        $description = new ClassDescription();
        $this->traverseWithVisitor($nodes, new ClassDescriptionVisitor($description));

        return $description;
    }
}