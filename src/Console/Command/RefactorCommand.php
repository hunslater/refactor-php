<?php
namespace RefactorPhp\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Jan Alfred Richter <falnyr@gmail.com>
 *
 * @internal
 */
final class RefactorCommand extends Command
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('refactor')
            ->setDescription('Refactors code.')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->write("Lorem ipsum");
    }
}
