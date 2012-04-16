<?php namespace Model; Use \_;
class Builder {
    private $pages = array();
    private $posts = array();

    private $changedPosts = array();

    public function __construct() {
        $cnt = 0;
        foreach (array_reverse(glob("data/posts/*.md")) as $file) {
            $data = new Data($file, 'post');
            if ($data) {
                $this->posts[] = $data;
                if (!$data->preview && $cnt++ < 5) {
                    $data->frontpage = true;                    
                }
            }
        }
        foreach (glob("data/pages/*.md") as $file) {
            $data = new Data($file, 'page');
            if ($data) $this->pages[] = $data;
        }
    }
    public static function makeUri($uri) { 
        $uri = lower($uri);
        $uri = str_replace(array(' ', 'ä',  'ö',  'ü',  'ß'), 
                           array('-', 'ae', 'oe', 'ue', 'ss'), 
                           $uri);
        $uri = preg_replace('#[^a-z0-9_\-\.]#', '', $uri);
        $uri = preg_replace('#-+#', '-', $uri);
        $uri = preg_replace('#_+#', '_', $uri);
        $uri = str_replace(array('_-_'), array('-'), $uri);
     
        return $uri; 
    }
    public static function makeLink($uri) {
        return preg_replace('#(\d{4})_(\d{2})_(\d{2})_(.*)#', '$1/$2/$3/$4', $uri);
    }
    public function checkCache($index_dir) {    
        if (!file_exists($index_dir)) mkdir($index_dir, null, true);
        
        $datas = array_merge($this->pages, $this->posts);
        $count = count($datas);
        foreach ($datas as $k => $data) {
            if ($cli = _::Registry()->get('CLI')) $cli->log($k+1 ." / $count");
            $index_file = $index_dir . $data->type . 's/' . $data->filename;
            $sha1 = sha1_file($data->file);       
            if (_::Request()->force !== null || 
            !file_exists($index_file) || file_get_contents($index_file) != $sha1) {
                // Update Cache   
                $this->updatePost($data, $index_dir, $index_file);
            }
        }
    }
    
    public function updatePost($data, $index_dir, $index_file) {
        $date = $data->getHeader('date');
        
        if (!$date) {
            $date = date('Y-m-d H:i:s');
            $data->setHeader('date', $date);       
        }
        
        if ($data->filename != $data->uri) {
            $data->rename($data->uri);
            $index_file = $index_dir . $data->type . 's/' . $data->filename;                    
        }
        
        // Update the cache file
        if (!$data->preview) {
            $cachefile = "cache/".$data->type."s/".$data->uri.".html";
            $date_upd = date('Y-m-d H:i:s');
            $data->setHeader('last_updated', $date_upd);
            
            $content = $data->getContent();
            $viewdata['title'] = $data->getHeader('title');
            $viewdata['link'] = $this->makeLink($data->uri) . '.html';
            $viewdata['content'] = markdown($content);
            $viewdata['date'] = $data->getFormattedDate();                    
            $viewdata['formattedDate'] = $data->getFormattedDate(true);                    
            $viewdata['formattedTags'] = $data->getFormattedTags();
            $viewdata['comments'] = $data->getComments();                    
           
            $this->fetchTemplate('_' . $data->type, $cachefile, $viewdata, array('title' => $viewdata['title']));
            
            if ($data->type == 'post') {
                $this->changedPosts[] = $data->file;
            }
        }
        
        $data->save();
        
        // Write the index
        file_put_contents($index_file, sha1($data->raw));
    }

    public function checkFrontpage() {
        $update = false;
        $frontpagePosts = array();
        foreach ($this->posts as $post) {
            if ($post->frontpage) {
                $frontpagePosts[] = $post;
                if ($post->modified) $update = true;               
            }
        }

        if ($update) {
            $posts = array();
            foreach ($frontpagePosts as $post) {      
                $posts[] = array(
                    'title' => $post->getHeader('title'),
                    'rawdate' => $post->getHeader('date'),
                    'date' => $post->getFormattedDate(),
                    'excerpt' => $post->getHeader('excerpt'),
                    'content' => markdown($post->getContent()),
                    'link' => $this->makeLink($post->filename) . '.html',
                    'guid' => $post->filename, 
                );                
            }

            $this->fetchTemplate('_frontpage', 'cache/index.html', array('posts' => $posts));
            
            // FEED
            $view = new \_View();
            $view->setLayout('feed');
           
            $view->context = 'layout';
            $view->setAll(array('items' => $posts));           
            
            ob_start();
            $view->render();
            $html = ob_get_contents();
            ob_end_clean(); 

            file_put_contents('cache/feed.xml', $html);
        }
    }
    
    public function buildPostMeta() {
        if ($this->changedPosts) {
            $archive_posts = $cats = array();
            foreach ($this->posts as $post) {
                if ($post->preview) continue;
                $year = date('Y', strtotime($post->getHeader('date')));
                $tags = $post->getHeader('tags');
                $data = array(
                    'title' => $post->getHeader('title'),
                    'date' => $post->getHeader('date'),
                    'excerpt' => $post->getHeader('excerpt'),
                    'link' => $this->makeLink($post->filename) . '.html',
                );
                foreach ($tags as $tag) {
                    $cats[$tag][] = $data;
                }
                $archive_posts[$year][] = $data;
            }
            foreach ($cats as $tag => $posts) {
                $tplname = $this->makeUri($tag);
                $this->fetchTemplate('_tags', 'cache/pages/'.$tplname.'.html', array('posts' => $posts, 'tag' => $tag));
            }
            $this->fetchTemplate('_archiv', 'cache/archiv.html', array('posts' => $archive_posts));
        }
    }
    public function getSidebar() {
        $featured = $newest = $tags = array();
        foreach ($this->posts as $k => $post) {
            if ($post->preview) continue;
            $tags = array_merge($tags, $post->getHeader('tags'));
            $arr = array(
                'title' => $post->getHeader('title'),               
                'link' => $this->makeLink($post->filename) . '.html',
            );
            if ($post->featured) { $featured[] = $arr; }
            if ($k < 3) { $newest[] = $arr; }
        }
        asort($tags);
        return array($featured, $newest, array_unique($tags));
    }
    
    
    public function cleanup() {
        // Clean cache dir
        foreach (glob("cache/posts/*.html") as $file) {
            $filename = basename($file, '.html');
            
            foreach ($this->posts as $data) {
                if ($data->preview) continue;
                if ($filename == $data->filename) continue 2;
            }
            
            unlink($file);
        }
        foreach (glob("cache/index/posts/*") as $file) {
            $filename = basename($file);
            
            foreach ($this->posts as $data) {
                if ($filename == $data->filename) continue 2;
            }
            unlink($file);
        }
    }
    
    private function fetchTemplate($tpl, $filename, $data, $layoutData = false) {
        $view = new \_View();
        $view->setLayout('layout');
        _::Controller()->handleSlots($view);
        
        if ($layoutData) {
            $view->context = 'layout';
            $view->setAll($layoutData);
        }
        
        $view->context = 'content';
        $view->setControllerTemplate($tpl);
        $view->setAll($data);
        
        ob_start();
        $view->render();
        $html = ob_get_contents();
        ob_end_clean(); 

        file_put_contents($filename, $html);
    }
}
