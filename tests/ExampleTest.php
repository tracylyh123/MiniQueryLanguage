<?php
namespace Test;

use MiniQueryLanguage\Translators\SqlStatementTranslator;
use MiniQueryLanguage\Input;
use MiniQueryLanguage\Lexer;
use MiniQueryLanguage\Parser;
use MiniQueryLanguage\Visitors\TreePrintVisitor;
use PHPUnit\Framework\TestCase;

class ExampleTest extends TestCase
{
    public function test1()
    {
        $query = "(or age:'6' (and weight:'30' (or name:'tracy' gender:'male' (not height:'100'))))";
        $visitor = new TreePrintVisitor();
        $parser = new Parser(new Lexer(new Input($query)), $visitor);
        $translator = new SqlStatementTranslator($parser->parse());
        $expected = "(`age`='6' or (`weight`='30' and (`name`='tracy' or `gender`='male' or (`height`!='100'))))";
        $this->assertEquals($expected, $translator->translate());

        $expected = <<<EXPECTED
	operator => or
	field => age, value => 6
		operator => and
		field => weight, value => 30
			operator => or
			field => name, value => tracy
			field => gender, value => male
				operator => not
				field => height, value => 100\n
EXPECTED;
        $this->assertEquals($expected, (string) $visitor);
    }
}
