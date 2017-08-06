<?php
namespace RefactorPhp\Processor;

use RefactorPhp\ClassBuilder;
use RefactorPhp\ClassDescription;
use RefactorPhp\Filesystem;
use RefactorPhp\Manifest\MergeClassInterface;
use RefactorPhp\Node\NodeParser;

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
            $classFrom = $this->parser->getClassDescription($source);
            $classTo = $this->parser->getClassDescription($destination);

            $this->mergeClasses($classFrom, $classTo);

            $classFrom = [$this->builder->buildFromDescription($classFrom)];
            $classTo = [$this->builder->buildFromDescription($classTo)];

            // Temporary path
            $this->fs->saveNodesToFile($classFrom, __DIR__.'/../../manifests/'.basename($source));
            $this->fs->saveNodesToFile($classTo, __DIR__.'/../../manifests/'.basename($destination));
        }
    }

    /**
     * @param ClassDescription $source
     * @param ClassDescription $destination
     */
    public function mergeClasses(ClassDescription $source, ClassDescription $destination)
    {
        foreach ($source->getImplements() as $name => $interface) {
            if ( ! array_key_exists($name, $destination->getImplements())) {
                $destination->addImplements($interface);
                $source->removeImplements($interface);
            }
        }

        foreach ($source->getTraits() as $name => $trait) {
            if ( ! array_key_exists($name, $destination->getTraits())) {
                $destination->addTrait($trait);
                $source->removeTrait($trait);
            }
        }

        foreach ($source->getConstants() as $name => $constant) {
            if ( ! array_key_exists($name, $destination->getConstants())) {
                $destination->addConstant($constant);
                $source->removeConstant($constant);
            }
        }

        foreach ($source->getProperties() as $name => $property) {
            if ( ! array_key_exists($name, $destination->getProperties())) {
                $destination->addProperty($property);
                $source->removeProperty($property);
            }
        }

        foreach ($source->getMethods() as $name => $method) {
            if ( ! array_key_exists($name, $destination->getMethods())) {
                $destination->addMethod($method);
                $source->removeMethod($method);
            }
        }

        dump("--- DUPLICATES ---");
        dump(count($source->getTraits()));
        dump(count($source->getConstants()));
        dump(count($source->getProperties()));
        dump(count($source->getMethods()));
    }
}
