# Changelog

Version 0.0.3
------------------------------

> September 3, 2018

### feat

 - **link:** add suport for command line interface
 - **link:** add `base()`, `index()` and `__toString()` methods

### fix

 - **http:** use `$preserveHost` parameter in `Puzzle\Component\Http\Request`
 - **http:** better way to know if stream is detached in `Puzzle\Component\Http\Stream`
 - **http:** change logic condition in `Puzzle\Component\Http\Stream`
 - **link:** change without logic in `Puzzle\Component\Link\EvolvableLink`
 - **navigation:** ignore matches if route does not correspond

### test

 - **core:** add unitary tests
 - **http:** add unitary tests
 - **link:** add unitary tests
 - **navigation:** add unitary tests
 - **render:** add unitary tests

### docs

 - update changelog

Version 0.0.2
-------------------------------

> August 10, 2018

### feat

 - make configuration more flexible
 - **http:** add possibility to configure the stream in `Puzzle\Component\Http\Stream`
 - **http:** add static `send()` method in `Puzzle\Component\Http\Response`
 - **navigation:** create navigation component

### fix

 - **core:** fix lifecycle execution

### refactor

 - add exception throwers
 - remove useless methods and constants
 - **core:** change lifecycle
 - **core:** change `matches` keyword to `slugs`
 - **http:** add the final `S` to `HTTP_METHODS` in `Puzzle\Component\Http\Message`

### docs

 - add changelog

Version 0.0.1
------------------------------

> August 1, 2018

First commit