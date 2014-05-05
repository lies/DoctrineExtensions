<?php


namespace DoctrineExtensions\Query\Mysql;


use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\Lexer;

/**
 * Class Week
 * @category DoctrineExtensions
 * @package DoctrineExtensions\Query\Mysql
 */
class Week extends FunctionNode
{
    /** @var string */
    private $date;
    /** @var string */
    private $mode;

    /**
     * @param \Doctrine\ORM\Query\SqlWalker $sqlWalker
     *
     * @return string
     */
    public function getSql(\Doctrine\ORM\Query\SqlWalker $sqlWalker)
    {
        return sprintf(
            "WEEK(%s %s)",
            $sqlWalker->walkArithmeticPrimary($this->date),
            $this->mode !== null ? ",{$sqlWalker->walkLiteral($this->mode)}" : ''
        );
    }

    /**
     * @param \Doctrine\ORM\Query\Parser $parser
     *
     * @return void
     */
    public function parse(\Doctrine\ORM\Query\Parser $parser)
    {
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);

        $this->date = $parser->ArithmeticPrimary();

        if (Lexer::T_COMMA === $parser->getLexer()->lookahead['type']) {
            $parser->match(Lexer::T_COMMA);
            $this->mode = $parser->Literal();
        }

        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }
}