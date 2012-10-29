<?php namespace Model; Use \_;
class Data {
    public $type = 'post'; // post|page|comment
    public $preview = false;
    public $frontpage = false;
    public $featured = false;
    
    private $filename;
    private $file;
    public $raw;
    private $content;
    private $header;
    public $modified = false;
    private $fetched = false;
    
    public function __construct($file, $type, $create = false) {
        $this->file = $file;
        $this->type = $type;
        $this->filename = basename($file, '.md');
        $this->preview = $this->filename[0] == '_' ? '_' : '';
        if ($this->filename[1] ==  '_') return false;
        
        
        //Todo: don't fetch this in the constructor... or do? idk
        
        
        if (!file_exists($file)) {
            if (!$create) return false;

        }
        
        // Load Raw
        if (!$create)
            $this->raw = file_get_contents($file);
        
        $header_end = strpos($this->raw, '---', strpos($this->raw, "\n")) + 4;
        // Load Header
        $header = ss($this->raw, 0, $header_end);   
        preg_match_all("#^([a-z]*): (.*)$#Uism", $header, $matches);

        foreach ($matches[1] as $k => $match) {
            $this->header[$match] = $matches[2][$k];
        }
        
        // Load Content    
        $this->content = trim(ss($this->raw, $header_end));

        if ($this->getHeader('featured')) {
            $this->featured = true;
        }
    }
    public function __get($name) {
        switch ($name) {
            case 'filename':
            case 'file':
                return $this->$name;
                break;
            case 'uri':
                if ($this->type == 'post') {
                    return $this->preview . date('Y_m_d_', strtotime($this->getHeader('date'))) . Builder::makeUri($this->getHeader('slug')?:$this->getHeader('title'));
                } else {
                    return $this->preview . Builder::makeUri($this->getHeader('slug')?:$this->getHeader('title'));
                }
                break;
        }
    }
    public function getHeader($key) {
        if ($key == 'excerpt' && !isset($this->header[$key])) {
            // Excerpt        
            $excerpt = strip_tags(markdown($this->content));
            if (len($excerpt) > 220) {
                $pos = strpos($excerpt, ' ', 220);
                if ($pos === false) $pos = 220;
                $excerpt = ss($excerpt, 0, $pos) . '&hellip;';
            }
            return $excerpt;            
        }
        if ($key == 'tags' && isset($this->header[$key])) {      
            $tags = array();
            foreach (explode(',', $this->header[$key]) as $v) {
                $v = trim($v);
                if (!in_array($v, $tags)) $tags[] = $v;
            }
            return $tags;
        }
        return isset($this->header[$key]) ? $this->header[$key] : false;
    }
    public function getContent() {
        return $this->content;
    }
    public function getFormattedDate($long = false) {
        $date = strtotime($this->getHeader('date'));        
        $months = explode(',', ',Januar,Februar,März,April,Mai,Juni,Juli,August,September,Oktober,November,Dezember');
        $days = explode(',', ',Montag,Dienstag,Mittwoch,Donnerstag,Freitag,Samstag,Sonntag');
        $month = date('n', $date);
        $day = date('N', $date);

        return !$long ? 
            sprintf(date('d. %\s Y', $date), $months[$month]) :
            sprintf(date('%\s, d. %\s Y', $date), $days[$day], $months[$month]);
    }
    public function getFormattedTags() {
        $tags = $this->getHeader('tags');
        
        if ($tags) {
            if (($cnt = count($tags)) > 1) {
                $last = $tags[$cnt-1];
                $tags[$cnt-1] = 'und';
                $tags[] = $last;
            }
            $tags = implode(', ', $tags);
            return str_replace(', und,', ' und ', $tags);
        }
        return 'n/a';
    }
    public function getComments() {
        $comments = array();
        if ($this->type == 'post') {
            foreach (glob("data/comments/".$this->filename."-*.md") as $file) {
                $data = new Data($file, 'comment');
                $title = '<em>unbekannt</em>';
                if ($name = $data->getHeader('name')) {
                    $title = ($www = $data->getHeader('www')) ?
                        '<a href="'.e($www).'">'.e($name).'</a>' :
                        e($name);
                }
                $md = new \Markdown_Parser();
                $comment = $md->doAutoLinks($data->getContent());
                $comment = e($comment);
                $comments[] = array(
                    'title' => $title,
                    'date' => $data->getHeader('date'),
                    'comment' => nl2br($md->unhash($comment)),
                    'filename' => $data->filename
                );
            }
        }

        return $comments; 
    }
    public function setHeader($key, $value) {
        $this->modified = true;
        $this->header[$key] = $value;        
    }
    public function setContent($value) {
        $this->modified = true;
        $this->content = trim($value);        
    }
    public function rename($uri) {
        rename($this->file, $file = 'data/'.$this->type . 's/' . $uri . '.md');
        $this->file = $file;
        $this->filename = $uri;
        
    }
    public function save() {
        if ($this->modified) {
            $out = "---\n";
            foreach ($this->header as $k => $v) {
                if ($k == 'date' && $v == 'now') $v = date('Y-m-d H:i:s');
                if ($k == 'last_updated') $out .= "\n";
                $out .= "$k: $v\n";
            }
            $out .= "---\n\n";
            
            $out .= trim($this->content); 
            
            $this->raw = $out;
            file_put_contents($this->file, $out);
        }
    }
    
}