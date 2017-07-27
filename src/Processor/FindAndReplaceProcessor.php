<?php
namespace RefactorPhp\Processor;

use PhpParser\PrettyPrinter\Standard;
use RefactorPhp\Exception\RuntimeException;
use RefactorPhp\Finder;
use RefactorPhp\Node\NodeParser;
use RefactorPhp\Visitor\Schema\RefactorFileVisitor;
use PhpParser\NodeVisitor;
use RefactorPhp\Filesystem;
use Symfony\Component\Finder\SplFileInfo;
use Throwable;

/**
 * Class RefactorProcessor.
 */
class FindAndReplaceProcessor extends FindProcessor
{
    /**
     * @var Filesystem
     */
    protected $fs;

    /**
     * FindAndReplaceProcessor constructor.
     * @param Finder $finder
     * @param NodeParser $parser
     * @param Filesystem $fs
     */
    public function __construct(Finder $finder, NodeParser $parser, Filesystem $fs)
    {
        parent::__construct($finder, $parser);

        $this->fs = $fs;
    }


    /**
     * @param SplFileInfo $file
     *
     * @throws RuntimeException
     */
    public function processFile(SplFileInfo $file)
    {
        try {
            $contents = $file->getContents();
            $statements = $this->parser->parse($contents);
            $statements = $this->traverser->traverse($statements);

            if ($this->getRefactoredNodesCount() > 0) {
                $code = $this->prettyPrinter->prettyPrintFile($statements);
                $this->fs->dumpFile($this->outputDir.'/'.$file->getRelativePathname(), $code);
            }

        } catch (Exception $e) {
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
