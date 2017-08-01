<?php
declare(strict_types=1);

namespace RefactorPhp\Processor;

use RefactorPhp\Manifest\ManifestResolver;
use RefactorPhp\Node\NodeParser;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class RefactorProcessor.
 */
abstract class AbstractProcessor implements ProcessorInterface
{
    /**
     * @var ManifestResolver
     */
    protected $resolver;

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
     * @param ManifestResolver $resolver
     * @param NodeParser $parser
     */
    public function __construct(ManifestResolver $resolver, NodeParser $parser)
    {
        $this->resolver = $resolver;
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
        foreach ($this->resolver->getFinder() as $file) {
            $this->parser->getFileNodes($file);
        }
    }
}