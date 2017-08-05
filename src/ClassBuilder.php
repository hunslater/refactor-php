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
     * @return Class_
     */
    public function buildFromDescription(ClassDescription $description): Class_
    {
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

        return $class->getNode();
    }

}