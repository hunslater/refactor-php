<?php
namespace RefactorPhp;

use PhpParser\Node;

class ClassDescription
{
    /**
     * @var Node[]
     */
    private $traits = [];
    /**
     * @var Node[]
     */
    private $constants = [];
    /**
     * @var Node[]
     */
    private $properties = [];
    /**
     * @var Node[]
     */
    private $methods = [];

    /**
     * @return Node[]
     */
    public function getTraits(): array
    {
        return $this->traits;
    }

    /**
     * @param Node $trait
     */
    public function addTrait(Node $trait)
    {
        $this->traits[] = $trait;
    }

    /**
     * @return Node[]
     */
    public function getConstants(): array
    {
        return $this->constants;
    }

    /**
     * @param Node $constant
     */
    public function addConstant(Node $constant)
    {
        $this->constants[] = $constant;
    }

    /**
     * @return Node[]
     */
    public function getProperties(): array
    {
        return $this->properties;
    }

    /**
     * @param Node $property
     */
    public function addProperty(Node $property)
    {
        $this->properties[] = $property;
    }

    /**
     * @return Node[]
     */
    public function getMethods(): array
    {
        return $this->methods;
    }

    /**
     * @param Node $method
     */
    public function addMethod(Node $method)
    {
        $this->methods[] = $method;
    }
}