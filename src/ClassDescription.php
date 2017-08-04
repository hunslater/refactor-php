<?php
namespace RefactorPhp;

use PhpParser\Node;

class ClassDescription
{
    /**
     * @var Node\Stmt\TraitUse[]
     */
    private $traits = [];
    /**
     * @var Node\Stmt\ClassConst[]
     */
    private $constants = [];
    /**
     * @var Node\Stmt\PropertyProperty[]
     */
    private $properties = [];
    /**
     * @var Node\Stmt\ClassMethod[]
     */
    private $methods = [];

    /**
     * @return Node\Stmt\TraitUse[]
     */
    public function getTraits(): array
    {
        return $this->traits;
    }

    /**
     * @param Node\Stmt\TraitUse $trait
     */
    public function addTrait(Node\Stmt\TraitUse $trait)
    {
        $this->traits[$trait->traits[0]->getFirst()] = $trait;
    }

    /**
     * @param Node\Stmt\TraitUse $trait
     */
    public function removeTrait(Node\Stmt\TraitUse $trait)
    {
        unset($this->traits[$trait->traits[0]->getFirst()]);
    }

    /**
     * @return Node\Stmt\ClassConst[]
     */
    public function getConstants(): array
    {
        return $this->constants;
    }

    /**
     * @param Node\Stmt\ClassConst $constant
     */
    public function addConstant(Node\Stmt\ClassConst $constant)
    {
        $this->constants[$constant->consts[0]->name] = $constant;
    }

    /**
     * @param Node\Stmt\ClassConst $constant
     */
    public function removeConstant(Node\Stmt\ClassConst $constant)
    {
        unset($this->constants[$constant->consts[0]->name]);
    }

    /**
     * @return Node\Stmt\PropertyProperty[]
     */
    public function getProperties(): array
    {
        return $this->properties;
    }

    /**
     * @param Node\Stmt\PropertyProperty $property
     */
    public function addProperty(Node\Stmt\PropertyProperty $property)
    {
        $this->properties[$property->name] = $property;
    }

    /**
     * @param Node\Stmt\PropertyProperty $property
     */
    public function removeProperty(Node\Stmt\PropertyProperty $property)
    {
        unset($this->properties[$property->name]);
    }

    /**
     * @return Node\Stmt\ClassMethod[]
     */
    public function getMethods(): array
    {
        return $this->methods;
    }

    /**
     * @param Node\Stmt\ClassMethod $method
     */
    public function addMethod(Node\Stmt\ClassMethod $method)
    {
        $this->methods[strtolower($method->name)] = $method;
    }

    /**
     * @param Node\Stmt\ClassMethod $method
     */
    public function removeMethod(Node\Stmt\ClassMethod $method)
    {
        unset($this->methods[strtolower($method->name)]);
    }
}