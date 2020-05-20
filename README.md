# MiniQueryLanguage

A custom mini query language written by PHP.

The basic syntax is (OPERATOR OPTIONS EXPRESSION)

The query syntax can be translated to simple sql statement currently.

For example:

- (or age:'6' (and weight:'30' (or name:'tracy' gender:'male' (not height:'100'))))
  - sql clause: (\`age\`='6' or (\`weight\`='30' and (\`name\`='tracy' or \`gender\`='male' or !(\`height\`='100')))) 
- (and age:['2','10'] (not gender:'male') name:'tracy')
  - sql clause: (\`age\`>='2' and \`age\`<='10' and !(\`gender\`='male') and \`name\`='tracy')
- (not age:[,'10'} gender:'male' (or name:'tracy' name:'cuixi'))
  - sql clause: !(\`age\`<'10' and \`gender\`='male' and (\`name\`='tracy' or \`name\`='cuixi'))
