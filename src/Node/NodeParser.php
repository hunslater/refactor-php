<?php
namespace RefactorPhp\Node;

use PhpParser\Parser;
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

    public function parse(SplFileInfo $file)
    {
        $contents = $file->getContents();
        $statements = $this->parser->parse($contents);
        $statements = $this->traverser->traverse($statements);
    }
}