<?php
declare(strict_types=1);

namespace RefactorPhp\Node;

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
     * @var array
     */
    protected $matchingFiles = [];

    /**
     * NodeParser constructor.
     * @param Parser $parser
     * @param NodeTraverser $traverser
     * @param ManifestInterface $manifest
     */
    public function __construct(Parser $parser, NodeTraverser $traverser, ManifestInterface $manifest)
    {
        $this->parser = $parser;
        $this->traverser = $traverser;
        $this->manifest = $manifest;
    }

    /**
     * @param SplFileInfo $file
     */
    public function parse(SplFileInfo $file)
    {
        $contents = $file->getContents();
        $statements = $this->parser->parse($contents);
        $statements = $this->traverser->traverse($statements);

        if ($this->traverser->matchesManifest($statements, $this->manifest)) {
            $this->matchingFiles[$file->getPathname()] = $statements;
        }
    }

    /**
     * @return array
     */
    public function getMatchingFiles(): array
    {
        return $this->matchingFiles;
    }


    public function refactorMatchingFiles()
    {
        // magic
    }
}