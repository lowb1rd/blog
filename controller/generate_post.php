<?php

require('vendor/markdown/markdown.php');

function getHeader($key, $header) {
    if (!preg_match("#$key: (.*)#", $header, $match)) {
        die("FATAL: No $key header");
    }

    return $match[1];
}

function makeUri($uri) {
    $uri = lower($uri);
    $uri = str_replace(array('ä', 'ö', 'ü', 'ß'), array('ae', 'oe', 'ue', 'ss'), $uri);
    $uri = preg_replace('#[^a-z0-9_-]#', '', $uri);
    $uri = preg_replace('#-+#', '-', $uri);
    $uri = preg_replace('#_+#', '_', $uri);
    $uri = str_replace(array('_-_'), array('-'), $uri);
 
    return $uri;
}

$activePosts = array();

foreach (glob("data/posts/*.md") as $file) {
    $filename = basename($file);
    
    $preview = '';
    if ($filename[0] == '_') {
        $preview = '_';
        
        if ($filename[1] == '_') continue;
    }
    
    $post = file_get_contents($file);
    $header_end = strpos($post, '---', strpos($post, "\n"));
    $header = ss($post, 0, $header_end);

    
    $last_updated = getHeader('last_updated', $header);
    $title = getHeader('title', $header);
    $date = getHeader('date', $header);
    
    if (!$date) {
        $date = date('Y-m-d H:i:s');
        $post = str_replace('date: ', "date: $date", $post);
    }
    $uri = makeUri($title);
    $fulluri = date('Y/m/d', strtotime($date)) . $uri;
        
    if ($filename != $preview.$fulluri.'.md') {
        rename($file, "data/posts/$preview$uri.md");
    }
    
    $cachefile = "cache/posts/$uri.html";
    $header_end = strpos($post, '---', strpos($post, "\n")) +4;
    
    if (!$preview) {
        $activePosts[] = $cachefile;
        
        if (!$last_updated || filemtime($filename) > strtotime($last_updated) ||
            !file_exists($cachefile)) {
            // Update the cache file
            $content = ss($post, $header_end);
            
            $view = new _View();
            $view->setLayout('layout');
            
            $view->context = 'content';
            $view->title = $title;
            $view->content = markdown($content);
            $view->date = $date;
            
            ob_start();
            $view->setControllerTemplate('_post');
            $view->render();
            $html = ob_get_contents();
            ob_end_clean();
           
            file_put_contents($cachefile, $html);
        }
    }
}
