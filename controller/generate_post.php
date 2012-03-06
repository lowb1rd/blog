<?php

require('vendor/markdown/markdown.php');
require('model/builder.php');





//--

$builder = new \Model\Builder();

$builder->checkCache('cache/index/');
$builder->cleanup();

$builder->checkFrontpage();

// Build the index
//im "update the cache" oben wird parallel (mit anderem template) der reine blogpost gecacht (im ordner single) und beim index builden dann einfach die fertigen snippets aneinandergereiht

return _View::ACTION;