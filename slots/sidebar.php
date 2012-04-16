<?php

$builder = _::Registry()->get('builder');

// featured
list($featured, $newest, $cats) = $builder->getSidebar();

$view->featured = $featured;
$view->newest = $newest;
$view->cats = $cats;



return _View::CONTROLLER;