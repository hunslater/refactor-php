<?php
namespace RefactorPhp\Processor;

use PhpParser\BuilderFactory;
use RefactorPhp\ClassBuilder;
use RefactorPhp\ClassDescription;
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
            $classFrom = $this->parser->getClassDescription($source);
            $classTo = $this->parser->getClassDescription($destination);

            $this->mergeClasses($classFrom, $classTo);
            $builder = new ClassBuilder(new BuilderFactory());

            $classFrom = [$builder->buildFromDescription($classFrom)];
            $classTo = [$builder->buildFromDescription($classTo)];

            $this->fs->saveNodesToFile($classFrom, __DIR__.'/../../manifests/'.basename($source));
            $this->fs->saveNodesToFile($classTo, __DIR__.'/../../manifests/'.basename($source));
        }
    }


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

        dump(count($source->getTraits()));
        dump(count($source->getConstants()));
        dump(count($source->getProperties()));
        dump(count($source->getMethods()));
    }
}
