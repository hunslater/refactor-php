<?php
declare(strict_types=1);

namespace RefactorPhp\Processor;

use LogicException;
use PhpParser\ParserFactory;
use PhpParser\PrettyPrinter\Standard;
use RefactorPhp\Filesystem as RefactorPhpFilesystem;
use RefactorPhp\Manifest\FindAndReplaceInterface;
use RefactorPhp\Manifest\FindInterface;
use RefactorPhp\Manifest\ManifestInterface;
use RefactorPhp\Manifest\ManifestResolver;
use RefactorPhp\Node\NodeParser;
use RefactorPhp\Node\NodeTraverser;
use Symfony\Component\Filesystem\Filesystem;

class ProcessorFactory
{
    /**
     * Processor binding to interfaces.
     */
    const PROCESSORS = [
        FindInterface::class            => FindProcessor::class,
        FindAndReplaceInterface::class  => FindAndReplaceProcessor::class,
    ];

    /**
     * @var ManifestResolver
     */
    private $resolver;

    /**
     * @param ManifestInterface $manifest
     * @return ProcessorInterface
     */
    public function create(ManifestInterface $manifest): ProcessorInterface
    {
        $this->resolver = new ManifestResolver($manifest);
        $interface = $this->resolver->getManifestInterface();

        if (array_key_exists($interface, self::PROCESSORS)) {
            $processor = self::PROCESSORS[$interface];
            switch ($processor) {
                case FindProcessor::class:
                    return $this->createFindProcessor();
                    break;
                case FindAndReplaceProcessor::class:
                    return $this->createFindAndReplaceProcessor();
                    break;
                default:
                    throw new LogicException(
                        "Processor $processor is not implemented."
                    );
            }
        } else {
            throw new LogicException(
                "Unsupported interface: $interface."
            );
        }
    }

    /**
     * @return FindAndReplaceProcessor
     */
    private function createFindAndReplaceProcessor(): FindAndReplaceProcessor
    {
        return new FindAndReplaceProcessor(
            $this->resolver->getFinder(),
            new NodeParser(
                (new ParserFactory())->create(ParserFactory::PREFER_PHP7),
                new NodeTraverser($this->resolver->getManifest())
            ),
            new RefactorPhpFilesystem(
                new Filesystem(),
                new Standard()
            )
        );
    }

    /**
     * @return FindProcessor
     */
    private function createFindProcessor(): FindProcessor
    {
        return new FindProcessor(
            $this->resolver->getFinder(),
            new NodeParser(
                (new ParserFactory())->create(ParserFactory::PREFER_PHP7),
                new NodeTraverser($this->resolver->getManifest())
            )
        );
    }
}
