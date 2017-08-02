<?php
declare(strict_types=1);

namespace RefactorPhp\Processor;

use RefactorPhp\Manifest\ManifestResolver;
use RefactorPhp\Node\NodeParser;
use RefactorPhp\Filesystem;

/**
 * Class RefactorProcessor.
 */
class FindAndReplaceProcessor extends AbstractProcessor
{
    /**
     * @var Filesystem
     */
    protected $fs;

    /**
     * @var array
     */
    protected $matchingFiles = [];

    /**
     * FindAndReplaceProcessor constructor.
     * @param ManifestResolver $resolver
     * @param NodeParser $parser
     * @param Filesystem $fs
     */
    public function __construct(ManifestResolver $resolver, NodeParser $parser, Filesystem $fs)
    {
        parent::__construct($resolver, $parser);

        $this->fs = $fs;
    }

    public function refactor()
    {
        // TODO: Figure out checking the right manifest, perhaps not passing via resolver?
        $manifest = $this->resolver->getManifest();

        foreach ($this->resolver->getFinder() as $file) {
            $nodes = $this->parser->getFileNodes($file);
            if ($this->parser->matchesManifest($nodes, $manifest)) {
                $this->matchingFiles[$file->getPathname()] = $nodes;
            }
        }

        $this->output->writeln(
            sprintf("Found %d files to Find and Replace.", count($this->matchingFiles))
        );

        // TODO: Iterate matching files, replace nodes by condition, save using $fs
        foreach ($this->matchingFiles as $fileName => $nodes) {
            $nodes = $this->parser->applyManifest($nodes, $manifest);
        }
    }
}
