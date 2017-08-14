<?php
namespace RefactorPhp\Tests;

use PhpParser\Node\Const_;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\Cast\String_;
use PhpParser\Node\Name;
use PhpParser\Node\Scalar\DNumber;
use PhpParser\Node\Scalar\LNumber;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassConst;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Property;
use PhpParser\Node\Stmt\PropertyProperty;
use PhpParser\Node\Stmt\Return_;
use PhpParser\Node\Stmt\TraitUse;
use PHPUnit\Framework\TestCase;
use RefactorPhp\ClassDescription;

class ClassDescriptionTest extends TestCase
{
    public function testName()
    {
        $description = new ClassDescription();
        $description->setName("ACME");

        $this->assertSame("ACME", $description->getName());
    }

    /**
     * @dataProvider getNames
     * @param $name1 Name
     * @param $name2 Name
     */
    public function testExtends($name1, $name2)
    {
        $description = new ClassDescription();
        $description->setExtends($name1);
        $this->assertSame($name1, $description->getExtends());

        $description->setExtends($name2);
        $this->assertSame($name2, $description->getExtends());
    }

    /**
     * @dataProvider getNames
     * @param $name1 Name
     * @param $name2 Name
     */
    public function testImplements($name1, $name2)
    {
        $description = new ClassDescription();
        $description->addImplements($name1);
        $this->assertSame(
            [
                $name1->getFirst() => $name1,
            ],
            $description->getImplements()
        );

        $description->addImplements($name2);
        $this->assertSame(
            [
                $name1->getFirst() => $name1,
                $name2->getFirst() => $name2,
            ],
            $description->getImplements()
        );

        $description->removeImplements($name1);
        $this->assertSame(
            [
                $name2->getFirst() => $name2,
            ],
            $description->getImplements()
        );
    }

    /**
     * @dataProvider getConstants
     * @param $const ClassConst
     */
    public function testConstants($const)
    {
        $description = new ClassDescription();
        $description->addConstant($const);
        $this->assertSame(
            [
                $const->consts[0]->name => $const,
            ],
            $description->getConstants()
        );

        $description->removeConstant($const);
        $this->assertSame(
            [],
            $description->getConstants()
        );
    }

    /**
     * @dataProvider getProperties
     * @param $property Property
     */
    public function testProperties($property)
    {
        $description = new ClassDescription();
        $description->addProperty($property);
        $this->assertSame(
            [
                $property->props[0]->name => $property,
            ],
            $description->getProperties()
        );

        $description->removeProperty($property);
        $this->assertSame(
            [],
            $description->getProperties()
        );
    }

    /**
     * @dataProvider getMethods
     * @param $method ClassMethod
     */
    public function testMethods($method)
    {
        $description = new ClassDescription();
        $description->addMethod($method);
        $this->assertSame(
            [
                strtolower($method->name) => $method,
            ],
            $description->getMethods()
        );

        $description->removeMethod($method);
        $this->assertSame(
            [],
            $description->getMethods()
        );
    }

    public function getNames()
    {
        return [
            [new Name("Foo"), new Name("Bar")],
        ];
    }

    public function getConstants()
    {
        return [
            [new ClassConst([new Const_('FOO', new LNumber(5)), new Const_('BAR', new Array_([1,2,3]))])],
            [new ClassConst([new Const_('BAZ', new DNumber(3.4)), new Const_('FOO', new \PhpParser\Node\Scalar\String_("foo"))])],
        ];
    }

    public function getProperties()
    {
        return [
            [
                new Property(
                    Class_::MODIFIER_PROTECTED,
                    [new PropertyProperty('Foo', new LNumber(5))]
                )
            ],
            [
                new Property(
                    Class_::MODIFIER_STATIC|Class_::MODIFIER_PUBLIC,
                    [new PropertyProperty('Bar', new Array_([0,1,3]))]
                )
            ],
        ];
    }

    public function getMethods()
    {
        return [
            [
                new ClassMethod(
                    Class_::MODIFIER_PRIVATE,
                    [new Return_(new LNumber(5))]
                )
            ],
            [
                new ClassMethod(
                    Class_::MODIFIER_STATIC|Class_::MODIFIER_PUBLIC,
                    [new PropertyProperty('Bar', new Array_([0,1,3]))]
                )
            ],
        ];
    }
}