<?php
declare(strict_types=1);

namespace RefactorPhp\Processor;
use RefactorPhp\Finder;
use RefactorPhp\Manifest\FindInterface;
use RefactorPhp\Node\NodeParser;

/**
 * Class RefactorProcessor.
 */
class FindProcessor extends AbstractProcessor
{
    /**
     * @var Finder
     */
    protected $finder;

    /**
     * @var FindInterface
     */
    protected $manifest;

    /**
     * FindProcessor constructor.
     * @param NodeParser $parser
     * @param Finder $finder
     * @param FindInterface $manifest
     */
    public function __construct(NodeParser $parser, Finder $finder, FindInterface $manifest)
    {
        parent::__construct($parser);

        $this->finder = $finder;
        $this->manifest = $manifest;
    }
}
