<?php
namespace RefactorPhp;

use PhpParser\Node;

class ClassDescription
{
    /**
     * @var string
     */
    private $name;
    /**
     * @var Node\Name
     */
    private $extends;
    /**
     * @var Node\Name[]
     */
    private $implements = [];
    /**
     * @var Node\Stmt\TraitUse[]
     */
    private $traits = [];
    /**
     * @var Node\Stmt\ClassConst[]
     */
    private $constants = [];
    /**
     * @var Node\Stmt\Property[]
     */
    private $properties = [];
    /**
     * @var Node\Stmt\ClassMethod[]
     */
    private $methods = [];

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return Node\Name
     */
    public function getExtends(): Node\Name
    {
        return $this->extends;
    }

    /**
     * @param Node\Name $extends
     */
    public function setExtends(Node\Name $extends)
    {
        $this->extends = $extends;
    }

    /**
     * @return Node\Name[]
     */
    public function getImplements(): array
    {
        return $this->implements;
    }

    /**
     * @param Node\Name $implements
     */
    public function addImplements(Node\Name $implements)
    {
        $this->implements[$implements->getFirst()] = $implements;
    }

    /**
     * @param Node\Name $implements
     */
    public function removeImplements(Node\Name $implements)
    {
        unset($this->implements[$implements->getFirst()]);
    }

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
     * @return Node\Stmt\Property[]
     */
    public function getProperties(): array
    {
        return $this->properties;
    }

    /**
     * @param Node\Stmt\Property $property
     */
    public function addProperty(Node\Stmt\Property $property)
    {
        $this->properties[$property->props[0]->name] = $property;
    }

    /**
     * @param Node\Stmt\Property $property
     */
    public function removeProperty(Node\Stmt\Property $property)
    {
        unset($this->properties[$property->props[0]->name]);
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