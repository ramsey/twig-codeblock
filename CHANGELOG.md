# ramsey/twig-codeblock Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](http://semver.org/spec/v2.0.0.html).

## 3.0.0 - 2025-03-08

### Added

- Introduced an `Attributes` class to act as a fully-typed data transfer object of `{% codeblock %}` attributes.

- Introduced a `Highlighter\HighlighterReference` class as a fully-typed data transfer object to specify which highlighter to use when compiling the Twig templates.

### Changed

- PHP 8.2 is the new minimum version required.

- Twig 3.11.2 is the new minimum version required.

- All classes in this package are now `final`.

- Types are now declared everywhere.

- [ramsey/pygments](https://github.com/ramsey/pygments) is no longer a required dependency; it is a suggested dependency.

### Deprecated

- Nothing.

### Removed

- Nothing.

### Fixed

- Nothing.

## 2.0.0 - 2017-04-18

* Upgrade dependencies to require PHP 5.6 as the minimum PHP version
* Use [ramsey/pygments](https://github.com/ramsey/pygments) for syntax highlighting with Pygments
* Quoted strings must now be used for `format` and `lang` attributes
* Use attributes for `title`, `link`, and `link_text`; stay closer to [Octopress Codeblock](https://github.com/octopress/codeblock)
* Introduce `class` and drop support for `end` and `range`
* Remove `phpopentag` attribute and intelligently choose to "start inline" when using Pygments
* Support extension for Twig 1.15 and up, including Twig 2.0 and up

## 1.1.0 - 2017-03-28

* Lock symfony/process dependency to `^2.3`
* Add integration tests and improve Travis CI build process

## 1.0.1 - 2016-03-17

* Add project [Contributor Code of Conduct](https://github.com/ramsey/twig-codeblock/blob/master/CODE_OF_CONDUCT.md)
* Update documentation and add contributing information
* Cleanup code according to Scrutinzer issues

## 1.0.0 - 2015-02-27

* Initial release
