<?php
declare(strict_types=1);

namespace RefactorPhp;

use PhpParser\BuilderFactory;
use PhpParser\Node\Stmt\Class_;

final class ClassBuilder
{
    /**
     * @var BuilderFactory
     */
    private $builder;

    /**
     * ClassBuilder constructor.
     * @param BuilderFactory $builder
     */
    public function __construct(BuilderFactory $builder)
    {
        $this->builder = $builder;
    }

    /**
     * @param ClassDescription $description
     * @return array
     */
    public function buildFromDescription(ClassDescription $description): array
    {
        $nodes = [];
        $class = $this->builder->class($description->getName());

        if ($extend = $description->getExtends()) {
            $class->extend($extend);
        }

        if ($implements = $description->getImplements()) {
            foreach ($implements as $implement) {
                $class->implement($implement);
            }
        }

        $class
            ->addStmts($description->getTraits())
            ->addStmts($description->getConstants())
            ->addStmts($description->getProperties())
            ->addStmts($description->getMethods());

        if (null !== $namespace = $description->getNamespace()) {
            $builtNamespace = $this->builder->namespace($namespace->name->getFirst());
            $nodes[] = $builtNamespace->getNode();
        }

        if ($uses = $description->getUseCases()) {
            foreach ($uses as $use) {
                $builtUse = $this->builder->use($use->uses[0]->name->getFirst());
                $nodes[] = $builtUse->getNode();
            }
        }

        $nodes[] = $class->getNode();

        return $nodes;
    }
}