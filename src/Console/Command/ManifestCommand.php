<?php
namespace RefactorPhp\Console\Command;

use RefactorPhp\Finder;
use RefactorPhp\Processor\ProcessorFactory;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Stopwatch\Stopwatch;

/**
 * @author Jan Alfred Richter <falnyr@gmail.com>
 */
final class ManifestCommand extends Command
{
    /**
     * @var Finder
     */
    private $finder;

    /**
     * @var Stopwatch
     */
    private $stopwatch;

    /**
     * RefactorCommand constructor.
     * @param Finder $finder
     * @param Stopwatch $stopwatch
     */
    public function __construct(Finder $finder, Stopwatch $stopwatch)
    {
        parent::__construct();

        $this->finder = $finder;
        $this->stopwatch = $stopwatch;
    }


    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('manifest')
            ->setDefinition(
                [
                    new InputArgument('file', InputArgument::REQUIRED, 'Manifest file.'),
                ]
            )
            ->setDescription('Performs refactoring based on manifest file.')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            require_once ($manifestFile = $input->getArgument('file'));
            $manifestClass = basename($manifestFile, '.php');
            $processor = (new ProcessorFactory($output->getVerbosity()))->create(new $manifestClass);
            dump($processor);
        } catch (\Exception $e) {
            var_dump($e->getMessage());
        }
    }
}
