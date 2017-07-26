<?php
namespace RefactorPhp\Tests\Manifest;

use PHPUnit\Framework\TestCase;
use RefactorPhp\Manifest\ManifestInterface;
use RefactorPhp\Manifest\ManifestResolver;

class ManifestResolverTest extends TestCase
{
    public function testResolveManifest()
    {
        $manifestInterface = $this->getMockBuilder(ManifestInterface::class)->getMock();
        $resolver = new ManifestResolver($manifestInterface);
        $this->assertAttributeEquals($manifestInterface, 'manifest', $resolver);
    }
}