<?php
namespace RefactorPhp\Processor;

use PhpParser\Node\Stmt\Class_;
use RefactorPhp\Filesystem;
use RefactorPhp\Finder;
use RefactorPhp\Manifest\MergeClassInterface;
use RefactorPhp\Node\NodeParser;
use Symfony\Component\Finder\SplFileInfo;

final class MergeClassProcessor extends AbstractProcessor
{
    /**
     * @var MergeClassInterface
     */
    protected $manifest;

    /**
     * @var Filesystem
     */
    protected $fs;

    /**
     * MergeClassProcessor constructor.
     * @param Finder $finder
     * @param NodeParser $parser
     * @param MergeClassInterface $manifest
     * @param Filesystem $fs
     */
    public function __construct(Finder $finder, NodeParser $parser, MergeClassInterface $manifest, Filesystem $fs)
    {
        parent::__construct($finder, $parser);

        $this->manifest = $manifest;
        $this->fs = $fs;
    }

    public function refactor()
    {
        foreach ($this->manifest->getClassMap() as $source => $destination) {
            $resultNodes = $this->parser->mergeClasses(
                $this->getClassNodes($source),
                $this->getClassNodes($destination)
            );

            $this->fs->saveNodesToFile($resultNodes[0], $source);
            $this->fs->saveNodesToFile($resultNodes[1], $destination);
        }
    }

    /**
     * @param string $filename
     * @return array
     */
    protected function getClassNodes(string $filename): array
    {
        $file = new SplFileInfo($filename, $filename, $filename);
        $nodes = $this->parser->getFileNodes($file, false);

        if (count($nodes) !== 1) {
            throw new \LogicException(sprintf(
                "Provided file %s contains non-class definitions.",
                basename($filename)
            ));
        }

        if ( ! $nodes[0] instanceof Class_) {
            throw new \LogicException(sprintf(
                "Unable to find class definition in %s.",
                basename($filename)
            ));
        }

        return $nodes;
    }
}
