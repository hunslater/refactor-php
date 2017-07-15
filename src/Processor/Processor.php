<?php
namespace RefactorPhp\Processor;

use PhpParser\NodeTraverser;
use PhpParser\ParserFactory;
use PhpParser\PrettyPrinter\Standard;
use RefactorPhp\Finder;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Class RefactorProcessor.
 */
class Processor implements ProcessorInterface
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
     * RefactorProcessor constructor.
     */
    public function __construct()
    {
        $this->parser = (new ParserFactory())->create(ParserFactory::PREFER_PHP7);
        $this->traverser = new NodeTraverser();
        $this->prettyPrinter = new Standard();
        $this->fs = new Filesystem();
        $this->finder = new Finder();
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