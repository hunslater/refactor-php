<?php
declare(strict_types=1);

namespace RefactorPhp\Node;

use LogicException;
use PhpParser\Node;
use PhpParser\Parser;
use RefactorPhp\Manifest\ManifestInterface;
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
     * @var ManifestInterface
     */
    private $manifest;

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
     * @param ManifestInterface $manifest
     */
    public function setManifest(ManifestInterface $manifest)
    {
        $this->manifest = $manifest;
    }

    /**
     * @param SplFileInfo $file
     * @return Node[]
     */
    public function getFileNodes(SplFileInfo $file): array
    {
        $contents = $file->getContents();
        $statements = $this->parser->parse($contents);

        return $this->traverser->traverse($statements);
    }

    /**
     * Traverses an array of nodes and determines whether it matches current manifest.
     *
     * @param Node[] $nodes Array of nodes
     * @return bool
     */
    public function matchesManifest(array $nodes): bool
    {
        if ( ! $this->manifest instanceof ManifestInterface) {
            throw new LogicException("Invalid manifest.");
        }

        return $this->traverser->matchesManifest($nodes, $this->manifest);
    }
}