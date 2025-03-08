<h1 align="center">ramsey/twig-codeblock</h1>

<p align="center">
    <strong>🌿 Syntax highlighting for Twig with the <code>{% codeblock %}</code> tag.</strong>
</p>

<p align="center">
    <a href="https://github.com/ramsey/twig-codeblock"><img src="https://img.shields.io/badge/source-ramsey/twig--codeblock-blue.svg?style=flat-square" alt="Source Code"></a>
    <a href="https://packagist.org/packages/ramsey/twig-codeblock"><img src="https://img.shields.io/packagist/v/ramsey/twig-codeblock.svg?style=flat-square&label=release" alt="Download Package"></a>
    <a href="https://php.net"><img src="https://img.shields.io/packagist/php-v/ramsey/twig-codeblock.svg?style=flat-square&colorB=%238892BF" alt="PHP Programming Language"></a>
    <a href="https://github.com/ramsey/twig-codeblock/blob/main/LICENSE"><img src="https://img.shields.io/packagist/l/ramsey/twig-codeblock.svg?style=flat-square&colorB=darkcyan" alt="Read License"></a>
    <a href="https://github.com/ramsey/twig-codeblock/actions/workflows/continuous-integration.yml"><img src="https://img.shields.io/github/actions/workflow/status/ramsey/twig-codeblock/continuous-integration.yml?branch=main&logo=github&style=flat-square" alt="Build Status"></a>
    <a href="https://codecov.io/gh/ramsey/twig-codeblock"><img src="https://img.shields.io/codecov/c/gh/ramsey/twig-codeblock?label=codecov&logo=codecov&style=flat-square" alt="Codecov Code Coverage"></a>
</p>

## About

Add code snippets with syntax highlighting and more to any [Twig][] template
with ramsey/twig-codeblock, a port of the `{% codeblock %}`
[Liquid tag for Octopress/Jekyll][octopress-codeblock].

ramsey/twig-codeblock includes an adapter for using [Pygments][], the Python
syntax highlighter, with [ramsey/pygments][], but it may use any syntax
highlighter. To use another syntax highlighter, implement `HighlighterInterface`
(see below for an example).

This project adheres to a [code of conduct](CODE_OF_CONDUCT.md).
By participating in this project and its community, you are expected to
uphold this code.

## Installation

Install this package as a dependency using [Composer](https://getcomposer.org).

```bash
composer require ramsey/twig-codeblock
```

## Usage

```
{% codeblock [attributes] %}
[lines of code]
{% endcodeblock %}
```

### Attributes

A number of attributes are available to `{% codeblock %}`:

| Attribute   | Example                      | Description                                                                                                    |
|-------------|------------------------------|----------------------------------------------------------------------------------------------------------------|
| `lang`      | `lang:"php"`                 | Tells the syntax highlighter the programming language being highlighted. Pass "plain" to disable highlighting. |
| `title`     | `title:"Figure 2."`          | Add a title to your code block.                                                                                |
| `link`      | `link:"https://example.com"` | Add a link to your code block title.                                                                           |
| `link_text` | `link_text:"Download Code"`  | Text to use for the link. Defaults to `"link"`.                                                                |
| `linenos`   | `linenos:false`              | Use `false` to disable line numbering. Defaults to `true`.                                                     |
| `start`     | `start:42`                   | Start the line numbering in your code block at this value.                                                     |
| `mark`      | `mark:4-6,12`                | Mark specific lines of code. This example marks lines 4, 5, 6, and 12.                                         |
| `class`     | `class:"myclass foo"`        | Add CSS class names to the code `<figure>` element.                                                            |
| `format`    | `format:"html"`              | The output format for the syntax highlighter. Defaults to "html."                                              |

> [!TIP]
> Order of attributes does not matter.

> [!WARNING]
> Not all highlighters will support all attributes. However, the Pygments
> highlighter does support each of these attributes.

### Example

``` twig
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

### Configuration

To use ramsey/twig-codeblock, create a `HighlighterReference` that defines the
highlighter you want to use. If using `PygmentsHighlighter`, by default, it will
look for `pygmentize` in your `PATH`.

``` php
use Ramsey\Twig\CodeBlock\CodeBlockExtension;
use Ramsey\Twig\CodeBlock\Highlighter\HighlighterReference;
use Ramsey\Twig\CodeBlock\Highlighter\PygmentsHighlighter;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

$reference = new HighlighterReference(PygmentsHighlighter::class);

$env = new Environment(new FilesystemLoader('/path/to/templates'));
$env->addExtension(new CodeBlockExtension($reference));
```

If `pygmentize` is not in the `PATH`, you may pass its location to the
highlighter reference:

``` php
$reference = new HighlighterReference(
    PygmentsHighlighter::class,
    ['/path/to/pygmentize'],
);
```

> [!NOTE]
> We use a `HighlighterReference` instead of an actual instance of
> `HighlighterInterface` because these values will be compiled into the Twig
> templates and cached for later execution.

### Pygments

This library provides `PygmentsHighlighter`, which depends on [ramsey/pygments][],
but ramsey/pygments is not a dependency, since you may use other highlighters
that implement `Ramsey\Twig\CodeBlock\Highlighter\HighlighterInterface`.

To use this library with ramsey/pygments, you must also require ramsey/pygments
as a dependency:

``` bash
composer require ramsey/pygments
```

Additionally, you will need to install [Python][] and [Pygments][] and ensure the
`pygmentize` CLI tool is available on your system. See the [Configuration](#configuration)
section for help configuring Codeblock if `pygmentize` is not in your `PATH`.

``` bash
pip install Pygments
```

#### Styles

A syntax highlighter, such as Pygments, requires a stylesheet for the markup it
generates. Pygments provides some stylesheets for you, which you may list from
the command line:

``` bash
pygmentize -L styles
```

To output and save one of these styles for use in your application, use
something like:

``` bash
pygmentize -S rainbow_dash -f html > rainbow_dash.css
```

Additionally, there are many custom Pygments styles found on the web, or you may
create your own.

#### Languages

If using Pygments, here are just a few of the languages (i.e., lexers) it supports:

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

``` bash
pygmentize -L lexers
```

### Using your own highlighter

If you have your own highlighter class that implements `Ramsey\Twig\CodeBlock\Highlighter\HighlighterInterface`,
you may create a `HighlighterReference` using it. The array of values passed
as the second argument will be passed to your class's constructor upon instantiation.

The arguments must be scalar values or arrays of scalar values, or they may be
expressions that evaluate to scalar values or arrays of scalar values. Null
values are also allowed. This restriction is because of the way these values are
compiled into the Twig templates.

``` php
use Ramsey\Twig\CodeBlock\CodeBlockExtension;
use Ramsey\Twig\CodeBlock\Highlighter\HighlighterReference;
use Ramsey\Twig\CodeBlock\Highlighter\PygmentsHighlighter;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Your\Own\Highlighter as MyHighlighter;

$reference = new HighlighterReference(MyHighlighter::class, [$arg1, $arg2, $arg3]);

$env = new Environment(new FilesystemLoader('/path/to/templates'));
$env->addExtension(new CodeBlockExtension($reference));
```

## Contributing

Contributions are welcome! To contribute, please familiarize yourself with
[CONTRIBUTING.md](CONTRIBUTING.md).

## Coordinated Disclosure

Keeping user information safe and secure is a top priority, and we welcome the
contribution of external security researchers. If you believe you've found a
security issue in software that is maintained in this repository, please read
[SECURITY.md](SECURITY.md) for instructions on submitting a vulnerability report.

## Copyright and License

The ramsey/twig-codeblock library is copyright © [Ben Ramsey](https://benramsey.com/)
and licensed for use under the MIT License (MIT). Please see [LICENSE](LICENSE)
for more information.

[octopress-codeblock]: https://github.com/octopress/codeblock
[python]: https://www.python.org
[pygments]: http://pygments.org/
[twig]: https://twig.symfony.com
[ramsey/pygments]: https://github.com/ramsey/pygments
