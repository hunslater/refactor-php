<?php
namespace RefactorPhp\Processor;

use PhpParser\NodeTraverser;
use PhpParser\NodeTraverserInterface;
use PhpParser\Parser;
use PhpParser\PrettyPrinter\Standard;
use RefactorPhp\Finder;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Class RefactorProcessor.
 */
abstract class AbstractProcessor implements ProcessorInterface
{
    /**
     * @var \PhpParser\Parser
     */
    protected $parser;
    /**
     * @var NodeTraverser
     */
    protected $traverser;
    /**
     * @var Standard
     */
    protected $prettyPrinter;
    /**
     * @var Filesystem
     */
    protected $fs;
    /**
     * @var Finder
     */
    protected $finder;

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
     * @param Parser $parser
     * @param NodeTraverserInterface $traverser
     * @param Standard $prettyPrinter
     * @param Filesystem $fs
     * @param Finder $finder
     */
    public function __construct(
        Parser $parser,
        NodeTraverserInterface $traverser,
        Standard $prettyPrinter,
        Filesystem $fs,
        Finder $finder
    )
    {
        $this->parser = $parser;
        $this->traverser = $traverser;
        $this->prettyPrinter = $prettyPrinter;
        $this->fs = $fs;
        $this->finder = $finder;
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