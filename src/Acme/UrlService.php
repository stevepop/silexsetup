<?php
namespace Acme;

class UrlService {
    private $urls = array(
        'goog' => 'http://www.google.com',
        'fb' => 'http://www.facebook.com',
    );

    public function get($short){
        return $this->urls[$short];
    }
}