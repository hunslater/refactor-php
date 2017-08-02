<?php
declare(strict_types=1);
namespace RefactorPhp;

use PhpParser\PrettyPrinter\Standard;
use Symfony\Component\Filesystem\Filesystem as BaseFilesystem;

/**
 * Filesystem decorator.
 * @package RefactorPhp
 */
final class Filesystem
{
    /**
     * @var BaseFilesystem
     */
    private $fs;
    /**
     * @var Standard
     */
    private $standard;

    /**
     * Filesystem constructor.
     * @param BaseFilesystem $fs
     * @param Standard $standard
     */
    public function __construct(BaseFilesystem $fs, Standard $standard)
    {
        $this->fs = $fs;
        $this->standard = $standard;
    }

    /**
     * Saves nodes to file.
     *
     * @param array $nodes
     * @param string $filename
     */
    public function saveNodesToFile(array $nodes, string $filename)
    {
        $code = $this->standard->prettyPrintFile($nodes);
        $this->fs->dumpFile($filename, $code);
    }

}