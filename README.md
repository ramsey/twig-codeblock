# CodeBlock Extension for Twig

Documentation to be updated and polished soon...

### Using the codeblock tag

Highlight your source code...

    {% codeblock lang:php %}
    namespace Foo;

    /**
     * Awesome Contrived Example.
     */
    class Bar implements BarInterface
    {
        private $baz;

        public function __construct(BazInterface $baz)
        {
            $this->baz = $baz;
        }

        public function doIt()
        {
            return $this->baz->do('it');
        }
    }
    {% endcodeblock %}


#### Pygments lexers

If you're using Pygments, here are just a few of the lexers it supports:

* bash, sh, ksh, shell
* console
* css+php
* css
* diff, udiff
* html+php
* html+twig
* html
* http
* js+php, javascript+php
* js, javascript
* json
* php, php3, php4, php5
* sass
* scss
* shell-session
* sql
* twig
* xml
* yaml
* zephir

To see more, type the following from the command line:

    pygmentize -L lexers


### Using the built-in Pygments helper

By default, the extension uses Pygments and, if `pygmentize` is in your `PATH`,
then you do not need to pass any arguments.

    services:
        rhumsaa.twig.codeblock_extension:
            class: Rhumsaa\Twig\CodeBlock\CodeBlockExtension
            tags:
                - { name: twig.extension }

However, if `pygmentize` is not in the `PATH`, then you may specify it's
location, like this:

    services:
        rhumsaa.twig.codeblock_extension:
            class: Rhumsaa\Twig\CodeBlock\CodeBlockExtension
            tags:
                - { name: twig.extension }
            arguments:
                - pygments
                - [/usr/local/bin/pygmentize]


### Using your own highlighter

If you have your own highlighter class that implements
`Rhumsaa\Twig\CodeBlock\Highlighter\HighlighterInterface`, then you may specify
the fully-qualified classname as the first argument to the extension. The second
argument is an array of 0-indexed values that will be passed as arguments to
your class constructor. Make sure that you specify them in the correct order as
your constructor requires.

    services:
        rhumsaa.twig.codeblock_extension:
            class: Rhumsaa\Twig\CodeBlock\CodeBlockExtension
            tags:
                - { name: twig.extension }
            arguments:
                - Your\Own\Highlighter
                - [arg1, arg2, arg3]
