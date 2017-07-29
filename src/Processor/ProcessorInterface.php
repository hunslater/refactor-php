<?php
declare(strict_types=1);

namespace RefactorPhp\Processor;

use Symfony\Component\Console\Output\OutputInterface;

interface ProcessorInterface
{
    public function setOutput(OutputInterface $output);

    public function refactor();
}