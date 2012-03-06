<?php namespace Model; Use \_;
class Data {
    public $type = 'post'; // post|page
    public $preview = false;
    public $frontpage = false;
    
    private $filename;
    private $file;
    private $raw;
    private $content;
    private $header;
    private $modified = false;
    
    public function __construct($file, $type) {
        $this->file = $file;
        $this->filename = basename($file, '.md');
        $this->preview = $this->filename[0] == '_' ? '_' : '';
        if ($this->filename[1] ==  '_') return false;
        
        // Load Raw
        $this->raw = file_get_contents($file);
        
        $header_end = strpos($this->raw, '---', strpos($this->raw, "\n")) + 4;
        // Load Header
        $header = ss($this->raw, 0, $header_end);   
        preg_match_all("#(.*): (.*)#Ui", $header, $matches)
        foreach ($matches as $match) {
            $this->header[$match[1]] = $match[2];
        }      
        
        // Load Content    
        $this->content = ss($this->raw, $header_end);
        
        // Excerpt
        if (!isset($this->header['excerpt']) {
            $excerpt = strip_tags(markdown($this->content));
            if (len($excerpt) > 100) {
                $excerpt = ss($excerpt, 0, 100) . '&hellip;';
            }
            $this->header['excerpt'] = $excerpt;
        }
    }
    public function __get($name) {
        switch ($name) {
            case 'filename':
            case 'file':
                return $this->$name;
                break;
            case 'uri':
                return $this->preview . date('Y_m_d_', strtotime($this->getHeader('date'))) . $this->makeUri($this->getHeader('title'));
                break;
        }
    }
    public function getHeader($key) {
        return isset($this->header[$key]) ? $this->header[$key] : false;
    }
    public function getContent() {
        $return $this->content;
    }
    public function setHeader($key, $value) {
        $this->modified = true;
        $this->header[$key] = $value;        
    }
    public function rename($uri) {
        rename($this->file, $file = 'data/'.$this->type . 's/' . $uri . '.md5');
        $this->file = $file;
        $this->filename = $uri;
        
    }
    public function save() {
        if ($this->modified) {
            $out = "---\n";
            foreach ($this->header as $k => $v) {
                if ($k == 'last_updated') $out .= "\n";
                $out .= "$k: $v\n";
            }
            $out .= "---\n\n";
            
            $out .= $this->content;
            
            $this->raw = $out;
            file_put_contents($this->file, $out);
        }
    }
    
}