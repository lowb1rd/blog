<?php

require('vendor/markdown/markdown_geshi.php');

$view = $this->view;
$builder = new \Model\Builder();
_::Registry()->set('builder', $builder);

$error = array();

$file = _::Request()->getParam('file');
$data = new \Model\Data('data/posts/'.$file.'.md', 'post');

if (!$data) {
    $T->do404();
    die();
}
$view->title = $data->getHeader('title');
$view->link = \Model\Builder::makeLink($data->uri);

if ($R->isPost()) {
    // Check Name
    if ($R->name && !ctype_print($R->name)) {
        $error['name'] = 'Der Name enthält ungültige Zeichen';
    } else if (len($R->passwd) > 40) {
        $error['name'] = 'Der Name darf maximal 40 Zeichen lang sein';
    }
    
    // Check E-Mail
    if ($R->email && filter_var($R->email, FILTER_VALIDATE_EMAIL, FILTER_FLAG_SCHEME_REQUIRED) === false) {
        $error['email'] = 'E-Mail-Adresse ungültig';
    } else if (len($R->email) > 100) {
        $error['email'] = 'E-Mail-Adresse zu lang';
    }
    
    // Check Website
    if ($R->www && filter_var($R->www, FILTER_VALIDATE_URL, FILTER_FLAG_SCHEME_REQUIRED) === false) {
        $error['www'] = 'Ungültige Adresse';
    } else if (len($R->email) > 100) {
        $error['www'] = 'Adresse zu lang';
    }
    
    // Check Comment
    if (!$R->comment) {
        $error['comment'] = 'Bitte Kommentar eingeben';
    } else if (len($R->comment) > 3000) {
        $error['comment'] = 'Kommentar zu lang';
    } else if (substr_count($R->comment, 'http') > 3) {
        $error['comment'] = 'Linkspam';
    }
    
    if (!$error) {
        // Check Spam
        $cache = _::Registry()->get('cache');
        $ip = ip2long($_SERVER['REMOTE_ADDR']);
        $cnt = $cache->get($ip);
        if ($cnt > 4) {
            $error['general'] = 'Spam!';
        } else {
            $cache->set($ip, $cnt+1, 3600);
        }
    }
    
    if (!$error) {
        // get the highest comment-nr
        $comments = $data->getComments();        
        $cnt = count($comments);
        $last = $comments[$cnt-1]['filename'];        
        $cnt = substr($last, strrpos($last, '-')+1);

        $comment = new \Model\Data('./data/comments/'.$data->filename.'-'.++$cnt.'.md', 'comment', true);
        $comment->setHeader('date', date('Y-m-d H:i:s'));
        $comment->setHeader('name', $R->name);
        $comment->setHeader('www', $R->www);
        $comment->setHeader('email', $R->email);
        $comment->setContent($R->comment);
        
        $comment->save();
        
        $index_dir = 'cache/index/';
        $index_file = $index_dir . $data->type . 's/' . $data->filename;
        $builder->updatePost($data, $index_dir, $index_file);
        
        $view->success = true;
    } else {
        $view->error = $error;
    }
}

return _View::CONTROLLER;