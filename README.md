# Codeblock Extension for Twig

Add code snippets with syntax highlighting and more to any [Twig][] template.

[![Source Code][badge-source]][source]
[![Latest Version][badge-release]][release]
[![Software License][badge-license]][license]
[![Build Status][badge-build]][build]
[![Coverage Status][badge-coverage]][coverage]
[![Total Downloads][badge-downloads]][downloads]

The Codeblock extension for Twig is a port of the [{% codeblock %} liquid tag for Octopress/Jekyll][octopress-codeblock].

By default, Codeblock uses [Pygments][], the Python syntax highlighter, to generate HTML markup suitable for highlighting blocks of code, but it may use any syntax highlighter. To use another syntax highlighter, simply implement `HighlighterInterface` (see below for an example).

This project adheres to a [Contributor Code of Conduct][conduct]. By participating in this project and its community, you are expected to uphold this code.


## Installation

The preferred method of installation is via [Packagist][] and [Composer][]. Run the following command to install the package and add it as a requirement to your project's `composer.json`:

```bash
composer require ramsey/twig-codeblock
```

## Usage

```
{% codeblock [options] %}
[lines of code]
{% endcodeblock %}
```
### Options

A number of options are available to Codeblock. Note that order does not matter.

Option       | Example                      | Description
------------ | ---------------------------- | ------------
`lang`       | `lang:"php"`                 | Tells the syntax highlighter the programming language being highlighted. Pass "plain" to disable highlighting.
`title`      | `title:"Figure 2."`          | Add a title to your code block.
`link`       | `link:"https://example.com"` | Add a link to your code block title.
`link_text`  | `link_text:"Download Code"`  | Text to use for the link. Defaults to `"link"`.
`linenos`    | `linenos:false`              | Use `false` to disable line numbering. Defaults to `true`.
`start`      | `start:42`                   | Start the line numbering in your code block at this value.
`mark`       | `mark:4-6,12`                | Mark specific lines of code. This example marks lines 4, 5, 6, and 12.
`class`      | `class:"myclass foo"`        | Add CSS class names to the code `<figure>` element.
`format`     | `format:"html"`              | The output format for the syntax highlighter. Defaults to "html."

### Example

```
{% codeblock lang:"php" %}
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
```


## Configuration

By default, Codeblock uses Pygments and, if `pygmentize` is in your `PATH`, then you do not need to pass any arguments.

``` php
use Ramsey\Twig\CodeBlock\CodeBlockExtension;

$env = new \Twig_Environment(new \Twig_Loader_Filesystem('/path/to/templates'));
$env->addExtension(new CodeBlockExtension());
```

If `pygmentize` is not in the `PATH`, you may specify its location:

``` php
use Ramsey\Twig\CodeBlock\CodeBlockExtension;

$env = new \Twig_Environment(new \Twig_Loader_Filesystem('/path/to/templates'));
$env->addExtension(
    new CodeBlockExtension('pygments', ['/usr/local/bin/pygmentize'])
);
```


## Pygments

By default, Pygments is used for highlighting code. You will need to [install Pygments][pygments] and ensure that the `pygmentize` CLI tool is available on your system. See the Configuration section for help configuring Codeblock if `pygmentize` is not in your `PATH`.

    pip install Pygments

### Styles

A syntax highlighter, such as Pygments, requires a stylesheet for the markup it generates. Pygments provides some stylesheets for you, which you may list from the command line:

    pygmentize -L styles

To output and save one of these styles for use in your application, use:

    pygmentize -S solarizedlight -f html > solarizedlight.css

Additionally, there are many custom Pygments styles found on the web, and you may create your own.

### Languages

If using Pygments, here are just a few of the languages (lexers) it supports:

* css
* diff
* html
* html+php
* javascript
* json
* php
* sass
* shell
* sql
* twig
* yaml

To see more, type the following from the command line:

    pygmentize -L lexers


## Using your own highlighter

If you have your own highlighter class that implements `Ramsey\Twig\CodeBlock\Highlighter\HighlighterInterface`, then you may specify the fully-qualified classname as the first argument to the extension. The second argument is an array of 0-indexed values that will be passed as arguments to your class constructor. Make sure that you specify them in the correct order as your constructor requires.

``` php
use Ramsey\Twig\CodeBlock\CodeBlockExtension;
use Your\Own\Highlighter as MyHighlighter;

$env = new \Twig_Environment(new \Twig_Loader_Filesystem('/path/to/templates'));
$env->addExtension(
    new CodeBlockExtension(MyHighlighter::class, ['arg1', 'arg2', 'arg3'])
);
```


## Contributing

Contributions are welcome! Please read [CONTRIBUTING][] for details.


## Copyright and license

The ramsey/twig-codeblock library is copyright © [Ben Ramsey][] and licensed for use under the MIT License (MIT). Please see [LICENSE][] for more information.


[badge-build]: https://img.shields.io/travis/ramsey/twig-codeblock/master.svg?style=flat-square
[badge-coverage]: https://img.shields.io/coveralls/ramsey/twig-codeblock/master.svg?style=flat-square
[badge-downloads]: https://img.shields.io/packagist/dt/ramsey/twig-codeblock.svg?style=flat-square
[badge-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[badge-release]: https://img.shields.io/github/release/ramsey/twig-codeblock.svg?style=flat-square
[badge-source]: http://img.shields.io/badge/source-ramsey/twig--codeblock-blue.svg?style=flat-square
[ben ramsey]: https://benramsey.com/
[build]: https://travis-ci.org/ramsey/twig-codeblock
[composer]: https://getcomposer.org
[conduct]: https://github.com/ramsey/twig-codeblock/blob/master/CODE_OF_CONDUCT.md
[contributing]: https://github.com/ramsey/twig-codeblock/blob/master/CONTRIBUTING.md
[coverage]: https://coveralls.io/r/ramsey/twig-codeblock?branch=master
[downloads]: https://packagist.org/packages/ramsey/twig-codeblock
[license]: https://github.com/ramsey/twig-codeblock/blob/master/LICENSE
[octopress-codeblock]: https://github.com/octopress/codeblock
[packagist]: https://packagist.org/packages/ramsey/twig-codeblock
[pygments]: http://pygments.org/
[release]: https://github.com/ramsey/twig-codeblock/releases
[source]: https://github.com/ramsey/twig-codeblock
[twig]: http://twig.sensiolabs.org/
