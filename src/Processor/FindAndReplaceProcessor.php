<?php
namespace RefactorPhp\Processor;

use RefactorPhp\Exception\RefactorException;
use RefactorPhp\Exception\RuntimeException;
use RefactorPhp\Visitor\Schema\RefactorFileVisitor;
use PhpParser\NodeVisitor;
use Symfony\Component\Finder\SplFileInfo;
use Throwable;

/**
 * Class RefactorProcessor.
 */
class FindAndReplaceProcessor extends RefactorProcessor
{
    /**
     * We are interested in PHP files only.
     */
    const DEFAULT_PATTERN = '*.php';

    /**
     * @var array
     */
    private $fileErrors = [];

    /**
     * @var int
     */
    private $processedFiles = 0;

    /**
     * @var array
     */
    private $visitors = [];

    /**
     * @var int
     */
    private $fileCount = 0;

    /**
     * @var array
     */
    private $excludedDirs;

    /**
     * @var string
     */
    private $filePattern;

    /**
     * @return array
     */
    public function getExcludedDirs(): array
    {
        return $this->excludedDirs;
    }

    /**
     * @param array $excludedDirs
     * @return $this
     */
    public function setExcludedDirs(array $excludedDirs)
    {
        $this->excludedDirs = $excludedDirs;

        return $this;
    }

    /**
     * @return string
     */
    public function getFilePattern(): string
    {
        return $this->filePattern ?? self::DEFAULT_PATTERN;
    }

    /**
     * @param string $filePattern
     * @return $this
     */
    public function setFilePattern(string $filePattern)
    {
        $this->filePattern = $filePattern;

        return $this;
    }

    /**
     * @param array $visitors
     *
     * @return $this
     */
    public function addVisitors(array $visitors)
    {
        foreach ($visitors as $visitor) {
            $this->addVisitor($visitor);
        }

        return $this;
    }

    /**
     * @param NodeVisitor $visitor
     *
     * @return $this
     */
    public function addVisitor(NodeVisitor $visitor)
    {
        $this->visitors[] = $visitor;
        $this->traverser->addVisitor($visitor);

        return $this;
    }

    /**
     * Performs refactor operations.
     */
    public function refactor()
    {
        try {
            $this->initializeFinder();
            foreach ($this->finder as $file) {
                $this->processFile($file);
            }
        } catch (RuntimeException $e) {
            $this->outputRuntimeException($e);
        } finally {
            echo 'REFACTORING COMPLETE.'.PHP_EOL;
            if (count($this->fileErrors) > 0) {
                echo 'REFACTOR ERRORS: '.PHP_EOL;
                print_r($this->fileErrors);
            }
        }
    }

    /**
     * @param SplFileInfo $file
     *
     * @throws RuntimeException
     */
    private function processFile(SplFileInfo $file)
    {
        try {
            $contents = $file->getContents();
            $statements = $this->parser->parse($contents);
            $statements = $this->traverser->traverse($statements);

            if ($this->getRefactoredNodesCount() > 0) {
                $code = $this->prettyPrinter->prettyPrintFile($statements);
                $this->fs->dumpFile($this->outputDir.'/'.$file->getRelativePathname(), $code);
            }

        } catch (RefactorException $e) {
            $this->recordException($file, $e);
        } catch (Throwable $e) {
            throw new RuntimeException(
                sprintf(
                    'Error refactoring %s.',
                    $file->getRelativePathname()
                ),
                0,
                $e
            );
        } finally {
            ++$this->processedFiles;
            echo sprintf(
                '%d/%d Completed file: %s with %s modified nodes',
                $this->processedFiles,
                $this->fileCount,
                $file->getRelativePathname(),
                $this->getRefactoredNodesCount()
            ).PHP_EOL;
        }
    }

    /**
     * @param SplFileInfo       $file
     * @param RefactorException $e
     */
    private function recordException(SplFileInfo $file, RefactorException $e)
    {
        $this->fileErrors[] = sprintf(
            '%s: %s in %s/%s:%s',
            (new \ReflectionClass($e))->getShortName(),
            $e->getMessage(),
            $file->getPath(),
            $file->getFilename(),
            $e->getCode()
        );
    }

    /**
     * @param RuntimeException $e
     */
    private function outputRuntimeException(RuntimeException $e)
    {
        $runtimeError = $e->getPrevious();
        echo sprintf(
            'FATAL: %s %s in %s:%s%s',
            $e->getMessage(),
            $runtimeError->getMessage(),
            $runtimeError->getFile(),
            $runtimeError->getLine(),
            PHP_EOL
        );
    }

    /**
     * Sets rules for file search.
     */
    private function initializeFinder()
    {
        $this->finder
            ->in($this->sourceDir)
            ->name($this->getFilePattern())
            ->exclude($this->getExcludedDirs());

        $this->fileCount = $this->finder->count();
        if ($this->outputDir === null) {
            $this->setOutputDir($this->sourceDir);
        }
    }

    /**
     * @return int
     */
    public function getRefactoredNodesCount(): int
    {
        $refactoredNodes = 0;
        foreach ($this->visitors as $visitor) {
            /** @var $visitor RefactorFileVisitor */
            $refactoredNodes+= $visitor->getRefactoredNodesCount();
        }

        return $refactoredNodes;
    }
}
