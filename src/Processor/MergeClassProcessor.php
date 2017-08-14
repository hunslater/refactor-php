<?php
namespace RefactorPhp\Processor;

use PhpParser\Node;
use RefactorPhp\ClassBuilder;
use RefactorPhp\ClassMerger;
use RefactorPhp\Filesystem;
use RefactorPhp\Manifest\MergeClassInterface;
use RefactorPhp\Node\NodeParser;
use RefactorPhp\Visitor\MergeExtractedMethodVisitor;
use RefactorPhp\Visitor\MergeUniqueMethodVisitor;

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
     * @var ClassBuilder
     */
    protected $builder;
    /**
     * @var ClassMerger
     */
    private $merger;

    /**
     * MergeClassProcessor constructor.
     * @param NodeParser $parser
     * @param MergeClassInterface $manifest
     * @param Filesystem $fs
     * @param ClassBuilder $builder
     * @param ClassMerger $merger
     */
    public function __construct(
        NodeParser $parser,
        MergeClassInterface $manifest,
        Filesystem $fs,
        ClassBuilder $builder,
        ClassMerger $merger
    )
    {
        parent::__construct($parser);

        $this->manifest = $manifest;
        $this->fs = $fs;
        $this->builder = $builder;
        $this->merger = $merger;
    }

    /**
     * {@inheritdoc}
     */
    public function refactor()
    {
        foreach ($this->manifest->getClassMap() as $source => $destination) {
            $this->output->writeln(sprintf(
                "Merging <comment>%s</comment> to <comment>%s</comment>...",
                basename($source),
                basename($destination)
            ));

            $this->merger
                ->setSourceClass($source)
                ->setDestinationClass($destination)
                ->merge();

            $sourceNodes = [$this->builder->buildFromDescription($this->merger->getSourceClass())];
            $resultNodes = [$this->builder->buildFromDescription($this->merger->getResultClass())];

            $this->saveFile($resultNodes, $destination);
            $this->saveFile($sourceNodes, $source);
        }
    }

    /**
     * @param $nodes Node[]
     * @param $filename
     */
    private function saveFile(array $nodes, string $filename)
    {
        $this->fs->saveNodesToFile($nodes, $filename);
        $this->output->writeln("Saved file <comment>$filename</comment>.");
    }
}