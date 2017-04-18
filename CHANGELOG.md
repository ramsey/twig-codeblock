# Codeblock Extension for Twig Changelog

## 2.0.0

_Released: 2017-04-18_

* Upgrade dependencies to require PHP 5.6 as the minimum PHP version
* Use [ramsey/pygments](https://github.com/ramsey/pygments) for syntax highlighting with Pygments
* Quoted strings must now be used for `format` and `lang` attributes
* Use attributes for `title`, `link`, and `link_text`; stay closer to [Octopress Codeblock](https://github.com/octopress/codeblock)
* Introduce `class` and drop support for `end` and `range`
* Remove `phpopentag` attribute and intelligently choose to "start inline" when using Pygments
* Support extension for Twig 1.15 and up, including Twig 2.0 and up

## 1.1.0

_Released: 2017-03-28_

* Lock symfony/process dependency to `^2.3`
* Add integration tests and improve Travis CI build process

## 1.0.1

_Released: 2016-03-17_

* Add project [Contributor Code of Conduct](https://github.com/ramsey/twig-codeblock/blob/master/CODE_OF_CONDUCT.md)
* Update documentation and add contributing information
* Cleanup code according to Scrutinzer issues

## 1.0.0

_Released: 2015-02-27_

* Initial release
