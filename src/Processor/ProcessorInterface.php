<?php
namespace RefactorPhp\Processor;

use Symfony\Component\Console\Output\OutputInterface;

interface ProcessorInterface
{
    public function setOutput(OutputInterface $output);
}