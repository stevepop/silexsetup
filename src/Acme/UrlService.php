<?php
namespace Acme;

class UrlService {
    private $url_list = array();
    private $url_file = '';
    const url_regex = '^(ht|f)tp(s?)\:\/\/[0-9a-zA-Z]([-.\w]*[0-9a-zA-Z])*(:(0-9)*)*(\/?)([a-zA-Z0-9\-\.\?\,\'\/\\\+&amp;%\$#_]*)?^';

    public function __construct($url_file_name) {
        $this->url_file = $url_file_name;
        $url_file = \parse_ini_file($url_file_name);

        foreach ($url_file as $slug => $url) {
            $this->url_list[$slug] = $url;
        }
    }

    public function getUrlSlug()
    {
        return $this->url_file;
    }

    public function get($url_slug) {
        return $this->url_list[$url_slug];
    }

    public function add($url_slug, $url) {
        if (!\preg_match(self::url_regex, $url)) {
            throw new \Exception('Invalid url');
        }
        if (isset($this->url_list[$url_slug])) {
            throw new \Exception('Url short name already exists');
        }
        $this->url_list[$url_slug] = $url;
        $this->dump();
    }

    private function dump() {
        $fh = fopen($this->url_file, 'w');
        foreach ($this->url_list as $url_slug => $url) {
            fwrite($fh, $url_slug . ' = ' . $url . "\n");
        }
        fclose($fh);
    }


    public function getAll(){
        return $this->url_list;
    }
}