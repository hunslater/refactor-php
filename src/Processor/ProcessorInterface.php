<?php
declare(strict_types=1);

namespace RefactorPhp\Processor;

use Symfony\Component\Console\Output\OutputInterface;

interface ProcessorInterface
{
    /**
     * @param OutputInterface $output
     */
    public function setOutput(OutputInterface $output);

    /**
     * Main processor procedure - executes the process of refactoring.
     */
    public function refactor();
}