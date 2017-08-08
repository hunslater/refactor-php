<?php
namespace RefactorPhp\Processor;

use RefactorPhp\ClassBuilder;
use RefactorPhp\ClassDescription;
use RefactorPhp\Filesystem;
use RefactorPhp\Manifest\MergeClassInterface;
use RefactorPhp\Node\NodeParser;
use RefactorPhp\Visitor\MergeExistingMethodVisitor;
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
     * MergeClassProcessor constructor.
     * @param NodeParser $parser
     * @param MergeClassInterface $manifest
     * @param Filesystem $fs
     * @param ClassBuilder $builder
     */
    public function __construct(NodeParser $parser, MergeClassInterface $manifest, Filesystem $fs, ClassBuilder $builder)
    {
        parent::__construct($parser);

        $this->manifest = $manifest;
        $this->fs = $fs;
        $this->builder = $builder;
    }

    public function refactor()
    {
        foreach ($this->manifest->getClassMap() as $source => $destination) {
            $this->output->writeln(sprintf(
                "Merging <comment>%s</comment> to <comment>%s</comment>...",
                basename($source),
                basename($destination)
            ));
            $classFrom = $this->parser->getClassDescription($source);
            $classTo = $this->parser->getClassDescription($destination);

            $this->mergeClasses($classFrom, $classTo);

            $classFrom = [$this->builder->buildFromDescription($classFrom)];
            $classTo = [$this->builder->buildFromDescription($classTo)];

            // Temporary path
            $this->fs->saveNodesToFile($classFrom, $source);
            $this->output->writeln("Saved file <comment>$source</comment>.");
            $this->fs->saveNodesToFile($classTo, $destination);
            $this->output->writeln("Saved file <comment>$destination</comment>.");
        }
    }

    /**
     * @param ClassDescription $source
     * @param ClassDescription $destination
     */
    public function mergeClasses(ClassDescription $source, ClassDescription $destination)
    {
        foreach ($source->getImplements() as $name => $interface) {
            $destination->addImplements($interface);
            $source->removeImplements($interface);
        }

        foreach ($source->getConstants() as $name => $constant) {
            $destination->addConstant($constant);
            $source->removeConstant($constant);
        }

        foreach ($source->getProperties() as $name => $property) {
            $destination->addProperty($property);
            $source->removeProperty($property);
        }

        foreach ($source->getMethods() as $name => $method) {
            if ( ! array_key_exists($name, $destination->getMethods())) {
                $this->parser->traverseWithVisitor([$method], new MergeUniqueMethodVisitor());
                $destination->addMethod($method);
                $source->removeMethod($method);
            } else {
                $this->parser->traverseWithVisitor([$method], new MergeExistingMethodVisitor());
                $this->output->writeln("<info>$name</info> will need refactoring manually.");
            }
        }
    }
}