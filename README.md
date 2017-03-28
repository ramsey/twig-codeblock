# Codeblock Extension for Twig

[![Source Code][badge-source]][source]
[![Latest Version][badge-release]][release]
[![Software License][badge-license]][license]
[![Build Status][badge-build]][build]
[![Coverage Status][badge-coverage]][coverage]
[![Total Downloads][badge-downloads]][downloads]

The {% codeblock %} extension for Twig is a port of the [Octopress codeblock liquid tag](https://github.com/octopress/codeblock) for use with the [Twig template engine for PHP](http://twig.sensiolabs.org/).

By default, Codeblock uses the [Pygments Python syntax highlighter](http://pygments.org/) for generating HTML markup suitable for highlighting blocks of code. However, it is flexible enough to use any syntax highlighter of your choice; simply implement the `HighlighterInterface` and provide some additional configuration (see below for an example).

This project adheres to a [Contributor Code of Conduct][conduct]. By participating in this project and its community, you are expected to uphold this code.

## Using the Codeblock tag

To highlight blocks of code, start the code block with the `{% codeblock %}` tag and end it with the `{% endcodeblock %}` tag. For example:

    {% codeblock lang:php %}
    <?php
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

A number of options are available to Codeblock:

Option      | Description
----------- | ------------
lang        | the programming language used in the code block
format      | the output format (defaults to "html")
linenos     | "true" to turn on line numbers (defaults to "false")
start       | starting line number, if linenos is "true"
end         | ending line number, if linenos is "true"
range       | starting and ending line numbers, overrides start and end (e.g. "101-118")
mark        | marks one or more lines of code to be highlighted in the output (e.g. "102,111-113,117")
phpopentag  | "false" to highlight PHP code without needing the PHP open tag (defaults to "true")

In addition to the options, you may also pass three text attributes. These are not named and must be wrapped in quotation marks (see example).

Attribute   | Description
----------- | ------------
title       | Provides a `<figcaption>` title for the code block
link        | Provides a link to the external source of the code, if desired
link text   | Provides the text to use for the link, otherwise "link" is used

A more complex example:

    {% codeblock lang:php phpopentag:false start:11 mark:3,8 "Methods on a Class" "http://example.com/full-listing" %}
    public function __construct(BazInterface $baz)
    {
        $this->baz = $baz;
    }

    public function doIt()
    {
        return $this->baz->do('it');
    }
    {% endcodeblock %}

## Output

If using "html" as the format output (which is the default), the resulting HTML output will wrap the highlighted code in a `<figure>` element. If you have provided a title and link, a `<figcaption>` will also be present.

Here's an example of HTML output that Pygments might generate, including the figure and figcaption elements provided by Codeblock (cleaned up for readability):

``` html
<figure class="code-highlight-figure">
    <figcaption class="code-highlight-caption">
        <span class="code-highlight-caption-title">Methods on a Class</span>
        <a class="code-highlight-caption-link" href="http://example.com/full-listing">link</a>
    </figcaption>
    <div class="highlight"><pre>

<span class="k">public</span> <span class="k">function</span> <span class="nf">__construct</span><span class="p">(</span><span class="nx">BazInterface</span> <span class="nv">$baz</span><span class="p">)</span>
<span class="p">{</span>
<span class="hll">    <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">baz</span> <span class="o">=</span> <span class="nv">$baz</span><span class="p">;</span>
</span><span class="p">}</span>

<span class="k">public</span> <span class="k">function</span> <span class="nf">doIt</span><span class="p">()</span>
<span class="p">{</span>
<span class="hll">    <span class="k">return</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">baz</span><span class="o">-&gt;</span><span class="na">do</span><span class="p">(</span><span class="s1">&#39;it&#39;</span><span class="p">);</span>
</span><span class="p">}</span>

    </pre></div>
</figure>
```

## Pygments

By default, Pygments is used for highlighting code. You will need to [install Pygments](http://pygments.org/) and ensure that the `pygmentize` CLI tool is available on your system. See the Configuration section for help configuring Codeblock if `pygmentize` is not in your `PATH`.

### Styles

A syntax highlighter, such as Pygments, requires a stylesheet for the markup it generates. Pygments provides some styles for you, which you may list from the command line:

    pygmentize -L styles

To output and save one of these styles for use in your application, use:

    pygmentize -S default -f html > default.css

Additionally, there are many custom Pygments styles found on the web, and you may create your own.

### Languages

If you're using Pygments, here are a few of the languages (lexers) it supports:

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

## Configuration

By default, Codeblock uses Pygments and, if `pygmentize` is in your `PATH`, then you do not need to pass any arguments.

### With pure PHP

``` php
use Ramsey\Twig\CodeBlock\CodeBlockExtension;

$env = new Twig_Environment(new Twig_Loader_Filesystem('/path/to/templates'));
$env->addExtension(new CodeBlockExtension());
```

If `pygmentize` is not in the `PATH`, you may specify its location:

``` php
use Ramsey\Twig\CodeBlock\CodeBlockExtension;

$env = new Twig_Environment(new Twig_Loader_Filesystem('/path/to/templates'));
$env->addExtension(
    new CodeBlockExtension('pygments', ['/usr/local/bin/pygmentize'])
);
```

### Register as a Symfony service

``` yaml
# app/config/services.yml
services:
    ramsey.twig.codeblock_extension:
        class: Ramsey\Twig\CodeBlock\CodeBlockExtension
        tags:
            - { name: twig.extension }
```

If `pygmentize` is not in the `PATH`, you may specify its location:

``` yaml
# app/config/services.yml
services:
    ramsey.twig.codeblock_extension:
        class: Ramsey\Twig\CodeBlock\CodeBlockExtension
        tags:
            - { name: twig.extension }
        arguments:
            - pygments
            - [/usr/local/bin/pygmentize]
```

## Using your own highlighter

If you have your own highlighter class that implements `Ramsey\Twig\CodeBlock\Highlighter\HighlighterInterface`, then you may specify the fully-qualified classname as the first argument to the extension. The second argument is an array of 0-indexed values that will be passed as arguments to your class constructor. Make sure that you specify them in the correct order as your constructor requires.

### With pure PHP

``` php
use Ramsey\Twig\CodeBlock\CodeBlockExtension;

$env = new Twig_Environment(new Twig_Loader_Filesystem('/path/to/templates'));
$env->addExtension(
    new CodeBlockExtension('Your\\Own\\Highlighter', ['arg1', 'arg2', 'arg3'])
);
```

### Register as a Symfony service

``` yaml
# app/config/services.yml
services:
    ramsey.twig.codeblock_extension:
        class: Ramsey\Twig\CodeBlock\CodeBlockExtension
        tags:
            - { name: twig.extension }
        arguments:
            - Your\Own\Highlighter
            - [arg1, arg2, arg3]
```

## Contributing

Contributions are welcome! Please read [CONTRIBUTING][] for details.

## Copyright and license

The ramsey/uuid library is copyright © [Ben Ramsey](https://benramsey.com/) and licensed for use under the MIT License (MIT). Please see [LICENSE][] for more information.


[badge-build]: https://img.shields.io/travis/ramsey/twig-codeblock/master.svg?style=flat-square
[badge-coverage]: https://img.shields.io/coveralls/ramsey/twig-codeblock/master.svg?style=flat-square
[badge-downloads]: https://img.shields.io/packagist/dt/ramsey/twig-codeblock.svg?style=flat-square
[badge-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[badge-release]: https://img.shields.io/github/release/ramsey/twig-codeblock.svg?style=flat-square
[badge-source]: http://img.shields.io/badge/source-ramsey/twig--codeblock-blue.svg?style=flat-square
[build]: https://travis-ci.org/ramsey/twig-codeblock
[conduct]: https://github.com/ramsey/twig-codeblock/blob/master/CODE_OF_CONDUCT.md
[contributing]: https://github.com/ramsey/twig-codeblock/blob/master/CONTRIBUTING.md
[coverage]: https://coveralls.io/r/ramsey/twig-codeblock?branch=master
[downloads]: https://packagist.org/packages/ramsey/twig-codeblock
[license]: https://github.com/ramsey/twig-codeblock/blob/master/LICENSE
[release]: https://github.com/ramsey/twig-codeblock/releases
[source]: https://github.com/ramsey/twig-codeblock
