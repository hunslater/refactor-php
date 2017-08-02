<?php
declare(strict_types=1);

namespace RefactorPhp\Tests;

use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamContainer;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Echo_;
use PhpParser\PrettyPrinter\Standard;
use PHPUnit\Framework\TestCase;
use RefactorPhp\Filesystem;
use Symfony\Component\Filesystem\Filesystem as SymfonyFilesystem;

class FilesystemTest extends TestCase
{
    /**
     * @var vfsStreamContainer
     */
    private $root;

    public function setUp()
    {
        $this->root = vfsStream::setup("refactor-php");
    }

    /**
     * @dataProvider getSaveFileData
     */
    public function testSaveNodes($nodes, $file)
    {
        $this->assertFalse($this->root->hasChild($file));

        $fs = new Filesystem(new SymfonyFilesystem(), new Standard());
        $savePath = vfsStream::url('refactor-php/'.$file);
        $fs->saveNodesToFile($nodes, $savePath);

        $this->assertTrue($this->root->hasChild($file));
        $this->assertSame("<?php\n\necho 'hello';", file_get_contents($savePath));
    }

    public function getSaveFileData()
    {
        return [
            [
                [new Echo_([new String_('hello')])],
                'hello.php',
            ],
        ];
    }
}