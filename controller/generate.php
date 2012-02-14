<?php

if ($file = $T->delegateAction()) {
    $return = include $file;
} else {
    $T->do404();
    die();
}

return $return;