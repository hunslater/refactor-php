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
        $this->assertFalse($this->root->hasChild($source['file']));
        $this->assertFalse($this->root->hasChild($destination['file']));

        $sourcePath = vfsStream::url('merge-classes/'.$source['file']);
        $destinationPath = vfsStream::url('merge-classes/'.$destination['file']);

        file_put_contents($sourcePath, $source['contents']);
        file_put_contents($destinationPath, $destination['contents']);

        $this->assertTrue($this->root->hasChild($source['file']));
        $this->assertTrue($this->root->hasChild($destination['file']));

        $manifestMock = $this->createMock(MergeClassInterface::class);
        $manifestMock->method('getClassMap')->willReturn([
            $sourcePath => $destinationPath,
        ]);

        $processor = (new ProcessorFactory())->create(new $manifestMock);
        $processor->refactor();

        $this->assertSame($result, file_get_contents($destinationPath));
    }

    /**
     * @return array
     */
    public function getRefactorData()
    {
        return [
            [
                [
                    'file' => 'foo.php',
                    'contents' => <<<'SOURCE'
<?php
class Foo extends Bar 
{
    const FOOBAR = 1;

    public function __construct($a, $b, $c)
    {
        parent::__construct();
        var_dump($a);
    }
    
    private function sniff()
    {
        return 1;
    }
}
SOURCE
        ],
                [
                    'file' => 'bar.php',
                    'contents' => <<<'DESTINATION'
<?php
class Bar
{
    const FOOBAR = 2;

    public function __construct($a)
    {
        var_dump(3);
    }
}
DESTINATION
                ],
                <<<'RESULT'
<?php
class Bar
{
    const FOOBAR = 1;

    public function __constructExtracted($a)
    {
        var_dump(3);
    }

    public function __construct($a, $b, $c)
    {
        self::__constructExtracted();
        var_dump($a);
    }

    private function sniff()
    {
        return 1;
    }
}
RESULT
            ],
        ];
    }
}