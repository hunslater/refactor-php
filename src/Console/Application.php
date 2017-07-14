<?php

namespace RefactorPhp\Console;

use PhpCsFixer\Console\Command\DescribeCommand;
use PhpCsFixer\Console\Command\FixCommand;
use PhpCsFixer\Console\Command\HelpCommand;
use PhpCsFixer\Console\Command\ReadmeCommand;
use PhpCsFixer\Console\Command\SelfUpdateCommand;
use RefactorPhp\Console\Command\RefactorCommand;
use Symfony\Component\Console\Application as BaseApplication;
use Symfony\Component\Console\Command\ListCommand;

/**
 * @author Jan Alfred Richter <falnyr@gmail.com>
 *
 * @internal
 */
final class Application extends BaseApplication
{
    const VERSION = '0.1.0-DEV';

    public function __construct()
    {
        error_reporting(-1);

        parent::__construct('Refactor PHP', self::VERSION);

        $this->add(new RefactorCommand());
    }

    /**
     * {@inheritdoc}
     */
    public function getLongVersion()
    {
        $version = parent::getLongVersion().' by <comment>PHParty</comment> New Zealand</comment>';
        $commit = '@git-commit@';

        if ('@'.'git-commit@' !== $commit) {
            $version .= ' ('.substr($commit, 0, 7).')';
        }

        return $version;
    }

    /**
     * {@inheritdoc}
     */
    protected function getDefaultCommands()
    {
        return [new \Symfony\Component\Console\Command\HelpCommand(), new ListCommand()];
    }
}
