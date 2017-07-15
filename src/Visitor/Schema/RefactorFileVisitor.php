<?php
namespace RefactorPhp\Visitor\Schema;

class RefactorFileVisitor extends StructuredVisitor
{
    /**
     * @var int
     */
    protected $refactoredNodes = 0;

    /**
     * @param array $nodes
     * @return array
     */
    public function beforeTraverse(array $nodes)
    {
        $this->rewindRefactoredNodes();

        return parent::beforeTraverse($nodes);
    }

    /**
     * Rewinds count of refactored nodes.
     */
    protected function rewindRefactoredNodes()
    {
        $this->refactoredNodes = 0;
    }

    /**
     * Rewinds count of refactored nodes.
     */
    protected function incrementRefactoredNodes()
    {
        $this->refactoredNodes++;
    }

    /**
     * @return int
     */
    public function getRefactoredNodesCount(): int
    {
        return $this->refactoredNodes;
    }
}
