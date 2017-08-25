PHP Refactor tool
==========================

[![Travis](https://img.shields.io/travis/phparty/refactor-php.svg)]()
[![Packagist](https://img.shields.io/packagist/v/phparty/refactor-php.svg)](https://packagist.org/packages/phparty/refactor-php)
[![Github All Releases](https://img.shields.io/packagist/dt/phparty/refactor-php.svg)]()

> This project is still experimental. Use at your own risk and backup your files.

The PHP Refactor tool is here to address your needs to refactor significant amounts
of code at once. 

PHP Code can be written in so many ways, using regular expressions to refactor pieces of code
can be overwhelming. PHP Refactor tool is using Abstract Syntax Tree (AST) language to correct all
the possible cases.

Requirements
------------

Only PHP version 7.0.0 and higher is supported.

How does it work?
-----------------

You can define the refactoring procedure using `Manifest` class.