<?php
namespace RefactorPhp\Processor;

use PhpParser\NodeTraverser;
use PhpParser\NodeTraverserInterface;
use PhpParser\Parser;
use RefactorPhp\Finder;

/**
 * Class RefactorProcessor.
 */
abstract class AbstractProcessor implements ProcessorInterface
{
    /**
     * @var Finder
     */
    protected $finder;
    /**
     * @var \PhpParser\Parser
     */
    protected $parser;
    /**
     * @var NodeTraverser
     */
    protected $traverser;
    /**
     * @var string
     */
    protected $sourceDir;

    /**
     * @var string
     */
    protected $outputDir;

    /**
     * AbstractProcessor constructor.
     * @param Finder $finder
     * @param Parser $parser
     * @param NodeTraverserInterface $traverser
     */
    public function __construct(
        Finder $finder,
        Parser $parser,
        NodeTraverserInterface $traverser
    )
    {
        $this->finder = $finder;
        $this->parser = $parser;
        $this->traverser = $traverser;
    }

    /**
     * @return string
     */
    public function getSourceDir(): string
    {
        return $this->sourceDir;
    }

    /**
     * @param string $sourceDir
     *
     * @return $this
     */
    public function setSourceDir(string $sourceDir)
    {
        $this->sourceDir = $sourceDir;

        return $this;
    }

    /**
     * @return string
     */
    public function getOutputDir(): string
    {
        return $this->outputDir;
    }

    /**
     * @param string $outputDir
     *
     * @return $this
     */
    public function setOutputDir(string $outputDir)
    {
        $this->outputDir = $outputDir;

        return $this;
    }
}