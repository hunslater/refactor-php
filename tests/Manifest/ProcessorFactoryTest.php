<?php
declare(strict_types=1);

namespace RefactorPhp\Tests\Manifest;

use PHPUnit\Framework\TestCase;
use RefactorPhp\Manifest\FindAndReplaceInterface;
use RefactorPhp\Manifest\FindInterface;
use RefactorPhp\Manifest\MergeClassInterface;
use RefactorPhp\Processor\FindAndReplaceProcessor;
use RefactorPhp\Processor\FindProcessor;
use RefactorPhp\Processor\MergeClassProcessor;
use RefactorPhp\Processor\ProcessorFactory;

class ProcessorFactoryTest extends TestCase
{
    /**
     * @dataProvider getData
     * @param $interface
     * @param $processor
     */
    public function testCreate($interface, $processor)
    {
        $factory = new ProcessorFactory();
        $interfaceMock = $this->createMock($interface);
        $processorObject = $factory->create($interfaceMock);

        $this->assertInstanceOf($processor, $processorObject);
    }

    public function getData()
    {
        return [
            [FindInterface::class, FindProcessor::class],
            [FindAndReplaceInterface::class, FindAndReplaceProcessor::class],
            [MergeClassInterface::class, MergeClassProcessor::class],
        ];
    }
}