<?php

require('vendor/markdown/markdown_geshi.php');

//--

_::Router(array(), array('ext' => '.html'));

$builder = new \Model\Builder();
_::Registry()->set('builder', $builder);

$cli = _::Registry()->get('CLI');

if ($cli) $cli->log('CheckCache');
$builder->checkCache('cache/index/');

if ($cli) $cli->log('ceanup');
$builder->cleanup();

if ($cli) $cli->log('checkFrontpage');
$builder->checkFrontpage();

if ($cli) $cli->log('buildPostMeta');
$builder->buildPostMeta();

if ($cli) $cli->log('DONE');
die();

