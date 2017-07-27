<?php
declare(strict_types=1);

namespace RefactorPhp\Tests\Manifest;

use PHPUnit\Framework\TestCase;
use RefactorPhp\Manifest\FindAndReplaceInterface;
use RefactorPhp\Manifest\ManifestResolver;

class ManifestResolverTest extends TestCase
{
    public function testResolveManifest()
    {
        $manifestInterface = $this->createMock(FindAndReplaceInterface::class);
        $resolver = new ManifestResolver($manifestInterface);

        $this->assertAttributeEquals($manifestInterface, 'manifest', $resolver);
        $this->assertSame(FindAndReplaceInterface::class, $resolver->getInterface());
    }
}