<?php

namespace RefactorPhp\Console;

use RefactorPhp\Console\Command\ManifestCommand;
use RefactorPhp\Finder;
use RefactorPhp\Manifest\ManifestResolver;
use Symfony\Component\Console\Application as BaseApplication;
use Symfony\Component\Console\Command\HelpCommand;
use Symfony\Component\Console\Command\ListCommand;
use Symfony\Component\Stopwatch\Stopwatch;

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

        parent::__construct('Refactor', self::VERSION);

        $this->add(
            new ManifestCommand(
                Finder::create(),
                new Stopwatch()
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getLongVersion()
    {
        $version = parent::getLongVersion().' by <comment>PHParty</comment>';
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
        return [
            new HelpCommand(),
            new ListCommand(),
        ];
    }
}
