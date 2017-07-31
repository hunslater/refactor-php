<?php
declare(strict_types=1);

namespace RefactorPhp\Tests\Manifest;

use PHPUnit\Framework\TestCase;
use RefactorPhp\Manifest\FindAndReplaceInterface;
use RefactorPhp\Manifest\FindInterface;
use RefactorPhp\Processor\FindAndReplaceProcessor;
use RefactorPhp\Processor\FindProcessor;
use RefactorPhp\Processor\ProcessorFactory;

class ProcessorFactoryTest extends TestCase
{
    public function testCreate()
    {
        $findAndReplaceInterface = $this->createMock(FindAndReplaceInterface::class);
        $findInterface = $this->createMock(FindInterface::class);

        $factory = new ProcessorFactory();
        $findAndReplaceProcessor = $factory->create($findAndReplaceInterface);
        $findProcessor = $factory->create($findInterface);

        $this->assertInstanceOf(FindAndReplaceProcessor::class, $findAndReplaceProcessor);
        $this->assertInstanceOf(FindProcessor::class, $findProcessor);
    }
}