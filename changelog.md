# Changelog

Version 0.0.3 (August ?, 2018)
------------------------------

### feat

 - **link:** add suport for command line interface

### fix

 - **http:** use `$preserveHost` parameter in `Puzzle\Component\Http\Request`
 - **navigation:** ignore matches if route does not correspond

### test

 - **navigation:** add unitary tests

Version 0.0.2 (August 10, 2018)
-------------------------------

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

Version 0.0.1 (August 1, 2018)
------------------------------

First commit