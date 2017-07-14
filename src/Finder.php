<?php
namespace RefactorPhp;

use Symfony\Component\Finder\Finder as BaseFinder;

/**
 * @author Jan Alfred Richter <falnyr@gmail.com>
 *
 */
class Finder extends BaseFinder
{
    public function __construct()
    {
        parent::__construct();

        $this
            ->files()
            ->name('*.php')
            ->ignoreDotFiles(true)
            ->ignoreVCS(true)
            ->exclude('vendor')
        ;
    }
}
