<?php
namespace Test;

use MiniQueryLanguage\Translators\SqlStatementTranslator;
use MiniQueryLanguage\Input;
use MiniQueryLanguage\Lexer;
use MiniQueryLanguage\Parser;
use PHPUnit\Framework\TestCase;

class SqlStatementTranslatorTest extends TestCase
{
    public function testTranslate()
    {
        $query = "(or age:'6' (and weight:'30' (or name:'tracy' gender:'male' (not height:'100'))))";
        $parser = new Parser(new Lexer(new Input($query)));
        $translator = new SqlStatementTranslator($parser->parse());
        $expected = "(`age`='6' or (`weight`='30' and (`name`='tracy' or `gender`='male' or !(`height`='100'))))";
        $this->assertEquals($expected, $translator->translate());

        $query = "(and age:['2','10'] (not gender:'male') name:'tracy')";
        $parser = new Parser(new Lexer(new Input($query)));
        $translator = new SqlStatementTranslator($parser->parse());
        $expected = "(`age`>='2' and `age`<='10' and !(`gender`='male') and `name`='tracy')";
        $this->assertEquals($expected, $translator->translate());

        $query = "(not age:[,'10'} gender:'male' (or name:'tracy' name:'cuixi'))";
        $parser = new Parser(new Lexer(new Input($query)));
        $translator = new SqlStatementTranslator($parser->parse());
        $expected = "!(`age`<'10' and `gender`='male' and (`name`='tracy' or `name`='cuixi'))";
        $this->assertEquals($expected, $translator->translate());
    }
}
