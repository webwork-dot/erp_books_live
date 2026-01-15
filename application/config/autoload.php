<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------
| AUTO-LOADER
| -------------------------------------------------------------------
| This file specifies which systems should be loaded by default.
|
| In order to keep the framework light, only the absolute minimal
| resources are loaded by default. By default, only the database
| and session are loaded.
|
| Prototype:
|
|	$autoload['libraries'] = array('database', 'email', 'session');
|
| You can also autoload helpers, models, and config files.
|
*/

$autoload['packages'] = array();

$autoload['libraries'] = array('database', 'session', 'form_validation');

$autoload['drivers'] = array();

$autoload['helper'] = array('url', 'form', 'html', 'theme', 'common_helper');

$autoload['config'] = array();

$autoload['language'] = array();

$autoload['model'] = array();

