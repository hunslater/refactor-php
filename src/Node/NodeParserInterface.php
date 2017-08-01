<?php
declare(strict_types=1);

namespace RefactorPhp\Node;

use Symfony\Component\Finder\SplFileInfo;

interface NodeParserInterface
{
    public function getFileNodes(SplFileInfo $file);
}