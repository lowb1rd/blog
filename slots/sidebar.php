<?php

$builder = _::Registry()->get('builder');
if (!$builder) $builder = new \Model\Builder();

// featured
list($featured, $newest, $cats) = $builder->getSidebar();

$view->featured = $featured;
$view->newest = $newest;
$view->cats = $cats;



return _View::CONTROLLER;