<?php

require('vendor/markdown/markdown_geshi.php');

//--

$xml = file_get_contents('tmp/alles.xml');
$xml = preg_replace("/(<\/?)(\w+):([^>]*>)/", "$1$2$3", $xml);
$xml = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
$xml = (array)$xml->channel;

foreach ($xml['item'] as $item) {
    $item = (array)$item;
    $title = $item['title'];
    $date = date('Y-m-d H:i:s', strtotime($item['pubDate']));
    echo $title."\n";

    if (isset($item['wpcomment'])) {
        if (!is_array($item['wpcomment'])) $item['wpcomment'] = array($item['wpcomment']);

        $item['wpcomment'] = array_reverse($item['wpcomment']);
        $file = date('Y_m_d_', strtotime($date)) . \Model\Builder::makeUri($title);
        $data = new \Model\Data('data/posts/'.$file.'.md', 'post');
        $comments = $data->getComments();
        $cnt = (int)count($comments);
        if ($cnt) $cnt--;
        
        
        
        foreach ((array)$item['wpcomment'] as $comment) {
            $comment = (array)$comment;
            $valid = $comment['wpcomment_approved'];
            $autor = $comment['wpcomment_author'];
            $mail = $comment['wpcomment_author_email'];
            $url = $comment['wpcomment_author_url'];
            $xdate = $comment['wpcomment_date'];
            $content = $comment['wpcomment_content'];
            if ($valid) {
              echo $autor."\n";
                

                $c = new \Model\Data('./data/comments/'.$data->filename.'-'.++$cnt.'.md', 'comment', true);
                $c->setHeader('date', $xdate);
                $c->setHeader('name', $autor);
                $c->setHeader('www', $url);
                $c->setHeader('email', $mail);
                $c->setContent($content);
                
                $c->save();
            }

        }
    }
    /* 
    $content = $item['contentencoded'];
    $date = $date = date('Y-m-d H:i:s', strtotime($item['pubDate']));
    $category = $item['category'];
    $slug = $item['wppost_name'];
    
    $data = new \Model\Data('data/posts/foo'.rand(0,999).'.md', 'post', true);
    $data->setHeader('date', $date);
    $data->setHeader('title', $title);
    $data->setHeader('tags', $category);
    $data->setHeader('slug', $slug);
    $data->setContent($content);
    $data->save();
    */
}
 
die();