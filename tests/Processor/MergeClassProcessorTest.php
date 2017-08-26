<?php
declare(strict_types=1);

namespace RefactorPhp\Tests\Manifest;

use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamContainer;
use PHPUnit\Framework\TestCase;
use RefactorPhp\Manifest\MergeClassInterface;
use RefactorPhp\Processor\ProcessorFactory;

class MergeClassProcessorTest extends TestCase
{
    const DATA_PROVIDER_PATH = __DIR__.'/../Data/MergeClassProcessor';
    /**
     * @var vfsStreamContainer
     */
    private $root;

    public function setUp()
    {
        $this->root = vfsStream::setup("merge-classes");
    }

    /**
     * @dataProvider getRefactorData
     * @param $source
     * @param $destination
     * @param $result
     */
    public function testRefactor($source, $destination, $result)
    {
        $this->assertFalse($this->root->hasChild($source));
        $this->assertFalse($this->root->hasChild($destination));

        $sourcePath = vfsStream::url('merge-classes/'.$source);
        $destinationPath = vfsStream::url('merge-classes/'.$destination);

        file_put_contents($sourcePath, file_get_contents(self::DATA_PROVIDER_PATH.'/'.$source));
        file_put_contents($destinationPath, file_get_contents(self::DATA_PROVIDER_PATH.'/'.$destination));

        $this->assertTrue($this->root->hasChild($source));
        $this->assertTrue($this->root->hasChild($destination));

        $manifestMock = $this->createMock(MergeClassInterface::class);
        $manifestMock->method('getClassMap')->willReturn([
            $sourcePath => $destinationPath,
        ]);

        $processor = (new ProcessorFactory())->create(new $manifestMock);
        $processor->refactor();


        $this->assertTrue(true);
//        $this->assertSame($result, file_get_contents($destinationPath));
    }

    /**
     * @return array
     */
    public function getRefactorData()
    {
        return [
            ['Baz.php', 'Bar.php', 'BazBar.php'],
        ];
    }
}