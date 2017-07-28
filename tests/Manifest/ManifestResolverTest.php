<?php
declare(strict_types=1);

namespace RefactorPhp\Tests\Manifest;

use PHPUnit\Framework\TestCase;
use RefactorPhp\Manifest\FindAndReplaceInterface;
use RefactorPhp\Manifest\FindInterface;
use RefactorPhp\Manifest\ManifestInterface;
use RefactorPhp\Manifest\ManifestResolver;

class ManifestResolverTest extends TestCase
{
    /**
     * @dataProvider getIncorrectManifestInterfaces
     */
    public function testIncorrectManifestInterface($interface)
    {
        $manifestInterface = $this->createMock($interface);
        $this->expectException(\LogicException::class);

        new ManifestResolver($manifestInterface);
    }

    /**
     * @dataProvider getManifestInterfaces
     */
    public function testManifestInterface($interface)
    {
        $manifestInterface = $this->createMock($interface);
        $resolver = new ManifestResolver($manifestInterface);

        $this->assertAttributeEquals($manifestInterface, 'manifest', $resolver);
        $this->assertSame($interface, $resolver->getManifestInterface());
    }

    public function getManifestInterfaces()
    {
        return [
            [FindAndReplaceInterface::class],
            [FindInterface::class],
        ];
    }

    public function getIncorrectManifestInterfaces()
    {
        return [
            [ManifestInterface::class],
        ];
    }
}