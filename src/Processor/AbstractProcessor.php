<?php
declare(strict_types=1);

namespace RefactorPhp\Processor;

use RefactorPhp\Node\NodeParser;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class RefactorProcessor.
 */
abstract class AbstractProcessor implements ProcessorInterface
{
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
     * @param NodeParser $parser
     */
    public function __construct(NodeParser $parser)
    {
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
    public function refactor() {}
}