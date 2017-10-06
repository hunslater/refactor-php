<?php
declare(strict_types=1);

namespace RefactorPhp;

use PhpParser\Node;
use PhpParser\Node\Stmt\Namespace_;
use PhpParser\Node\Stmt\Trait_;
use PhpParser\Node\Stmt\Use_;

class ClassDescription
{
    /**
     * @var Namespace_|null
     */
    private $namespace;
    /**
     * @var Use_[]
     */
    private $useCases = [];
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
     * @var
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
     * @return Namespace_|null
     */
    public function getNamespace()
    {
        return $this->namespace;
    }

    /**
     * @param Namespace_ $namespace
     * @return $this
     */
    public function setNamespace(Namespace_ $namespace)
    {
        $this->namespace = $namespace;

        return $this;
    }

    /**
     * @return Use_[]
     */
    public function getUseCases(): array
    {
        return $this->useCases;
    }

    /**
     * @param Use_ $useCase
     * @return $this
     */
    public function addUseCase(Use_ $useCase)
    {
        $this->useCases[$useCase->uses[0]->alias] = $useCase;

        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName(string $name)
    {
        $this->name = $name;

        return $this;
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
     * @return $this
     */
    public function setExtends(Node\Name $extends)
    {
        $this->extends = $extends;

        return $this;
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
     * @return $this
     */
    public function addImplements(Node\Name $implements)
    {
        $this->implements[$implements->getFirst()] = $implements;

        return $this;
    }

    /**
     * @param Node\Name $implements
     */
    public function removeImplements(Node\Name $implements)
    {
        unset($this->implements[$implements->getFirst()]);
    }

    /**
     * @return Trait_[]
     */
    public function getTraits(): array
    {
        return $this->traits;
    }

    /**
     * @param Trait_ $trait
     * @return $this
     */
    public function addTrait(Trait_ $trait)
    {
        $this->traits[$trait->name] = $trait;

        return $this;
    }

    /**
     * @param Trait_ $trait
     */
    public function removeTrait(Trait_ $trait)
    {
        unset($this->implements[$trait->name]);
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
     * @return $this
     */
    public function addConstant(Node\Stmt\ClassConst $constant)
    {
        $this->constants[$constant->consts[0]->name] = $constant;

        return $this;
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
     * @return $this
     */
    public function addProperty(Node\Stmt\Property $property)
    {
        $this->properties[$property->props[0]->name] = $property;

        return $this;
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
     * @return $this
     */
    public function addMethod(Node\Stmt\ClassMethod $method)
    {
        $this->methods[strtolower($method->name)] = $method;

        return $this;
    }

    /**
     * @param Node\Stmt\ClassMethod $method
     */
    public function removeMethod(Node\Stmt\ClassMethod $method)
    {
        unset($this->methods[strtolower($method->name)]);
    }
    
    /**
     * @param Node\Stmt\ClassMethod $method
     * @return bool
     */
    public function hasMethod(Node\Stmt\ClassMethod $method)
    {
        return array_key_exists(strtolower($method->name), $this->methods);
    }
}
