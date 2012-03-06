<?php namespace Model; Use \_;
class Builder {
    private $pages = array();
    private $posts = array();
    
    // some links
    private $frontpagePosts = array();
    private $activePosts = array();
    private $previewPosts = array();

    public function __construct() {
        $cnt = 0;
        foreach (array_reverse(glob("data/posts/*.md")) as $file) {
            $data = new Data($file, 'post');
            if ($data) {
                $this->posts[] = $data;
                if (!$data->preview) {
                    $activePosts[] = $data;
                    if ($cnt++ < 5) $frontpagePosts[] = $data;                    
                } else {
                    $previewPosts[] = $data;
                }
            }
        }
        foreach (glob("data/pages/*.md") as $file) {
            $data = new Data($file, 'page');
            if ($data) $this->pages[] = $data;
        }
    }
    private function makeUri($uri) {
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
    private function makeLink($uri) {
        return preg_replace('#(\d{4})_(\d{2})_(\d{2})_(.*)#', '$1/$2/$3/$4', $uri);
    }
    public function checkCache($index_dir) {    
        if (!file_exists($index_dir)) mkdir($index_dir, null, true);
        foreach (array_merge($this->pages, $this->posts) as $data) {
            $index_file = $index_dir . $data->type . 's/' . $data->filename;
            $sha1 = sha1_file($data->file);            
            if (_::Request()->force !== null || 
            !file_exists($index_file) || file_get_contents($index_file) != $sha1) {
                // Update Cache   
                $date = $data->getHeader('date');

                if (!$date) {
                    $date = date('Y-m-d H:i:s');
                    $data->setHeader('date', $date);       
                }
                
                if ($filename != $data->uri) {
                    $data->rename($data->uri);
                    $index_file = $index_dir . $data->type . 's/' . $data->filename;                    
                }
                
                // Update the cache file
                if (!$data->preview) {
                    $cachefile = "cache/".$data->type."s/".$data->uri.".html";
                    $date_upd = date('Y-m-d H:i:s');
                    $data->setHeader('last_updated', $date_upd);
                    
                    $content = $data->getContent();
                   
                    $view = new \_View();
                    $view->setLayout('layout');
                    
                    $view->context = 'content';
                    $view->setControllerTemplate('_' . $data->type); // _post|_page
                    $view->title = $data->getHeader('title');
                    $view->link = $this->makeLink($uri) . '.html';
                    $view->content = markdown($content);
                    $view->date = strtotime($date);
                    
                    //$cache = _::Cache(new CacheBackendVar());
                    //$cache->start();
                    ob_start();
                    $view->render();
                    //$cache->stop();
                    //$html = $cache->get();
                    $html = ob_get_contents();
                    ob_end_clean();
                    
                    file_put_contents($cachefile, $html);
                    
                    $this->changedPosts[] = $data->file;
                }
                
                $data->save();
                
                // Write the index
                file_put_contents($index_file, sha1($data->raw));
            }
        }
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
                    'title' => $data->getHeader('title'),
                    'date' => $data->getHeader('date'),
                    'excerpt' => $post->getHeader('excerpt'),
                    'link' => $this->makeLink($data->filename) . '.html',
                );                
            }
            $view = new \_View();
            $view->setLayout('layout');
            
            $view->context = 'content';
            $view->setControllerTemplate('_frontpage');
            $view->posts = $posts;
            
            ob_start();
            $view->render();
            $html = ob_get_contents();
            ob_end_clean(); 

            file_put_contents("cache/index.html", $html);
        }
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
}
