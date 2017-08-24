<?php
declare(strict_types=1);

namespace RefactorPhp;

use RefactorPhp\Node\NodeParser;
use RefactorPhp\Visitor\MergeClass\DetermineExtractedMethodsVisitor;
use RefactorPhp\Visitor\MergeClass\GetDestinationMethodsVisitor;
use RefactorPhp\Visitor\MergeClass\SetParentMethodCallsVisitor;
use RefactorPhp\Visitor\MergeClass\SetSelfMethodCallsVisitor;

class ClassMerger
{
    /**
     * @var NodeParser
     */
    private $parser;

    /**
     * @var ClassDescription
     */
    private $sourceClass;

    /**
     * @var ClassDescription
     */
    private $destinationClass;

    /**
     * @var ClassDescription
     */
    private $resultClass;

    /**
     * ClassMerger constructor.
     * @param NodeParser $parser
     */
    public function __construct(NodeParser $parser)
    {
        $this->parser = $parser;
    }

    /**
     * @param string $source
     * @return $this
     */
    public function setSourceClass(string $source)
    {
        $this->sourceClass = $this->parser->getClassDescription($source);

        return $this;
    }

    /**
     * @param string $destination
     * @return $this
     */
    public function setDestinationClass(string $destination)
    {
        $this->destinationClass = $this->parser->getClassDescription($destination);

        return $this;
    }

    public function merge()
    {
        $this->initialiseResultClass();
        $this->mergeInterfaces();
        $this->mergeConstants();
        $this->mergeProperties();
        $this->mergeMethods();
    }

    /**
     * @return ClassDescription
     */
    public function getResultClass(): ClassDescription
    {
        return $this->resultClass;
    }

    private function mergeInterfaces()
    {
        foreach ($this->destinationClass->getImplements() as $interface) {
            $this->resultClass->addImplements($interface);
        }

        foreach ($this->sourceClass->getImplements() as $interface) {
            $this->resultClass->addImplements($interface);
            $this->sourceClass->removeImplements($interface);
        }
    }

    private function mergeConstants()
    {
        foreach ($this->destinationClass->getConstants() as $constant) {
            $this->resultClass->addConstant($constant);
        }

        foreach ($this->sourceClass->getConstants() as $constant) {
            $this->resultClass->addConstant($constant);
            $this->sourceClass->removeConstant($constant);
        }
    }

    private function mergeProperties()
    {
        foreach ($this->destinationClass->getProperties() as $property) {
            $this->resultClass->addProperty($property);
        }

        foreach ($this->sourceClass->getProperties() as $property) {
            $this->resultClass->addProperty($property);
            $this->sourceClass->removeProperty($property);
        }
    }

    private function initialiseResultClass()
    {
        $this->resultClass = new ClassDescription();
        $this->resultClass
            ->setName($this->destinationClass->getName())
            ->setExtends($this->destinationClass->getExtends());
    }

    private function mergeMethods()
    {
        $destinationMethods = array_keys($this->destinationClass->getMethods());
        $extractedMethods = $this->getExtractedMethods();

        // modify parent::call to $this->callExtracted if necessary
        $parentMethodCallsVisitor = new SetParentMethodCallsVisitor($extractedMethods, $destinationMethods);
        foreach ($this->sourceClass->getMethods() as $methodName => $methodNode) {
            $this->parser->traverseWithVisitor([$methodNode], $parentMethodCallsVisitor);
        }

        foreach ($this->sourceClass->getMethods() as $method) {
            $this->resultClass->addMethod($method);
            $this->sourceClass->removeMethod($method);
        }

        // modify static::call to static::callExtracted if necessary
        $selfMethodCallsVisitor = new SetSelfMethodCallsVisitor($extractedMethods);
        foreach ($this->destinationClass->getMethods() as $methodName => $methodNode) {
            $this->parser->traverseWithVisitor([$methodNode], $selfMethodCallsVisitor);
        }

        foreach ($this->destinationClass->getMethods() as $method) {
            $this->resultClass->addMethod($method);
        }
    }

    /**
     * Gets list of methods that need extraction.
     * E.g. (parent::methodCall()) needs to be extracted to methodCallExtracted.
     * @return array
     */
    private function getExtractedMethods()
    {
        $extractedMethodsVisitor = new DetermineExtractedMethodsVisitor(
            array_keys($this->destinationClass->getMethods())
        );
        foreach ($this->sourceClass->getMethods() as $methodName => $methodNode) {
            $extractedMethodsVisitor->setMethodName($methodName);
            $this->parser->traverseWithVisitor([$methodNode], $extractedMethodsVisitor);
        }

        return $extractedMethodsVisitor->getExtractedMethods();
    }

    /**
     * @return ClassDescription
     */
    public function getSourceClass(): ClassDescription
    {
        return $this->sourceClass;
    }
}