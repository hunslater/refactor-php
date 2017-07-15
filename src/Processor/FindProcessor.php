<?php
namespace RefactorPhp\Processor;

use PhpParser\NodeVisitor;
use RefactorPhp\Exception\RefactorException;
use RefactorPhp\Exception\RuntimeException;
use Symfony\Component\Finder\SplFileInfo;
use Throwable;

/**
 * Class RefactorProcessor.
 */
class FindProcessor extends Processor implements FileProcessorInterface, RefactorProcessorInterface
{
    /**
     * @var array
     */
    protected $fileErrors = [];

    /**
     * @var int
     */
    protected $fileCount = 0;

    /**
     * @var array
     */
    protected $excludedDirs = [];

    /**
     * @var string
     */
    protected $findPattern;

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
    public function getFindPattern(): string
    {
        return $this->findPattern;
    }

    /**
     * @param string $findPattern
     * @return $this
     */
    public function setFindPattern(string $findPattern)
    {
        $this->findPattern = $findPattern;

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
    public function processFile(SplFileInfo $file)
    {
        try {
            echo $file->getPathname()."<br>";
            $contents = $file->getContents();
            $statements = $this->parser->parse($contents);
            $this->traverser->traverse($statements);
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
        }
    }

    /**
     * @param SplFileInfo       $file
     * @param RefactorException $e
     */
    protected function recordException(SplFileInfo $file, RefactorException $e)
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
    protected function outputRuntimeException(RuntimeException $e)
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
    protected function initializeFinder()
    {
        $this->finder
            ->in($this->sourceDir)
            ->name($this->getFindPattern())
            ->exclude($this->getExcludedDirs());

        $this->fileCount = $this->finder->count();
    }
}
