<?php
namespace Test;

use MiniQueryLanguage\Input;
use MiniQueryLanguage\Lexer;
use PHPUnit\Framework\TestCase;

class LexerTest extends TestCase
{
    public function testNext()
    {
        $query = "(or age:'6' (and weight:'30' (or name:'tracy' gender:'male' (not height:'100'))))";
        $lexer = new Lexer(new Input($query));
        $this->assertEquals('(', $lexer->current()->getValue());
        $lexer->next();
        $this->assertEquals('or', $lexer->current()->getValue());
        $lexer->next();
        $this->assertEquals('age', $lexer->current()->getValue());
        $lexer->next();
        $this->assertEquals('6', $lexer->current()->getValue());
        $lexer->next();
        $this->assertEquals('(', $lexer->current()->getValue());
        $lexer->next();
        $this->assertEquals('and', $lexer->current()->getValue());
        $lexer->next();
        $this->assertEquals('weight', $lexer->current()->getValue());
        $lexer->next();
        $this->assertEquals('30', $lexer->current()->getValue());
        $lexer->next();
        $this->assertEquals('(', $lexer->current()->getValue());
        $lexer->next();
        $this->assertEquals('or', $lexer->current()->getValue());
        $lexer->next();
        $this->assertEquals('name', $lexer->current()->getValue());
        $lexer->next();
        $this->assertEquals('tracy', $lexer->current()->getValue());
        $lexer->next();
        $this->assertEquals('gender', $lexer->current()->getValue());
        $lexer->next();
        $this->assertEquals('male', $lexer->current()->getValue());
        $lexer->next();
        $this->assertEquals('(', $lexer->current()->getValue());
        $lexer->next();
        $this->assertEquals('not', $lexer->current()->getValue());
        $lexer->next();
        $this->assertEquals('height', $lexer->current()->getValue());
        $lexer->next();
        $this->assertEquals('100', $lexer->current()->getValue());
        $lexer->next();
        $this->assertEquals(')', $lexer->current()->getValue());
        $lexer->next();
        $this->assertEquals(')', $lexer->current()->getValue());
        $lexer->next();
        $this->assertEquals(')', $lexer->current()->getValue());
        $lexer->next();
        $this->assertEquals(')', $lexer->current()->getValue());
        $lexer->next();
        $this->assertTrue($lexer->current()->typeIs(Lexer::T_EOF));
    }
}
