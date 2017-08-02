<?php
declare(strict_types=1);

namespace RefactorPhp\Processor;

use RefactorPhp\Finder;
use RefactorPhp\Node\NodeParser;
use Symfony\Component\Console\Output\OutputInterface;

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
     * @var NodeParser
     */
    protected $parser;

    /**
     * @var OutputInterface
     */
    protected $output;

    /**
     * AbstractProcessor constructor.
     * @param Finder $finder
     * @param NodeParser $parser
     */
    public function __construct(Finder $finder, NodeParser $parser)
    {
        $this->finder = $finder;
        $this->parser = $parser;
    }

    /**
     * {@inheritdoc}
     */
    public function setOutput(OutputInterface $output)
    {
        $this->output = $output;
    }

    /**
     * {@inheritdoc}
     */
    public function refactor()
    {
        $this->output->writeln($this->output->getVerbosity());
        foreach ($this->finder as $file) {
            $this->parser->getFileNodes($file);
        }
    }
}