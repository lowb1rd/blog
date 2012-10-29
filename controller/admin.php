<?php

require('vendor/markdown/markdown_geshi.php');

$builder = new \Model\Builder();
_::Registry()->set('builder', $builder);
$view = $this->view;

$prev = $live = array();
foreach ($builder->posts as $data) {
    if ($data->preview) {
        $prev[] = array('title' => $data->getHeader('title'), 'filename' => $data->filename);
    } else {
        $live[] = array('title' => $data->getHeader('title'), 'filename' => $data->filename);
    }
}

$view->prev = $prev;
$view->live = $live;


return _View::ACTION;