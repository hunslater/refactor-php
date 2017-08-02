<?php
declare(strict_types=1);

namespace RefactorPhp\Processor;

use RefactorPhp\Finder;
use RefactorPhp\Manifest\FindAndReplaceInterface;
use RefactorPhp\Node\NodeParser;
use RefactorPhp\Filesystem;

/**
 * Class RefactorProcessor.
 */
final class FindAndReplaceProcessor extends AbstractProcessor
{
    /**
     * @var Filesystem
     */
    protected $fs;

    /**
     * @var FindAndReplaceInterface
     */
    protected $manifest;

    /**
     * @var array
     */
    protected $matchingFiles = [];

    /**
     * FindAndReplaceProcessor constructor.
     * @param Finder $finder
     * @param NodeParser $parser
     * @param FindAndReplaceInterface $manifest
     * @param Filesystem $fs
     */
    public function __construct(Finder $finder, NodeParser $parser, FindAndReplaceInterface $manifest, Filesystem $fs)
    {
        parent::__construct($finder, $parser);

        $this->manifest = $manifest;
        $this->fs = $fs;
    }

    /**
     * {@inheritdoc}
     */
    public function refactor()
    {
        foreach ($this->finder as $file) {
            $nodes = $this->parser->getFileNodes($file);
            if ($this->parser->matchesManifest($nodes, $this->manifest)) {
                $this->output->writeln("<comment>Found {$file->getFilename()} matches manifest rules.</comment>");
                $this->matchingFiles[$file->getPathname()] = $nodes;
            }
        }

        $this->output->writeln(
            sprintf("Found %d files to Find and Replace.", count($this->matchingFiles))
        );

        foreach ($this->matchingFiles as $fileName => $nodes) {
            $this->output->writeln("<comment>Applying manifest rules to $fileName...</comment>");
            $nodes = $this->parser->applyManifest($nodes, $this->manifest);
            $this->fs->saveNodesToFile($nodes, $fileName);
            $this->output->writeln("Done.");
        }
    }
}
