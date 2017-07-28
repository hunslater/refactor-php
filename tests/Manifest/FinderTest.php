<?php
declare(strict_types=1);

namespace RefactorPhp\Tests\Manifest;

use PHPUnit\Framework\TestCase;
use RefactorPhp\Finder;
use Symfony\Component\Finder\Finder as SymfonyFinder;

class FinderTest extends TestCase
{
    public function testSymfonyFinder()
    {
        $finder = new Finder();
        $this->assertInstanceOf(SymfonyFinder::class, $finder);
    }
}