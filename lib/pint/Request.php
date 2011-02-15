<?php

namespace pint;

use \pint\Connection;

/**
 * 
 * 
 */
class Request implements \ArrayAccess
{
    /**
     *
     * @var array
     */
    protected $container = array(
        'headers' => array(),
        'method'  => null,
        'version' => null,
        'uri' => null
    );
    
    /**
     *
     * @var array
     */
    protected $status = array(
        100 => "Continue",
        101 => "Switching Protocols",
        200 => "OK",
        201 => "Created",
        202 => "Accepted",
        203 => "Non-Authoritative Information",
        204 => "No Content",
        205 => "Reset Content",
        206 => "Partial Content",
        300 => "Multiple Choices",
        301 => "Moved Permanently",
        302 => "Found",
        303 => "See Other",
        304 => "Not Modified",
        305 => "Use Proxy",
        307 => "Temporary Redirect",
        400 => "Bad Request",
        401 => "Unauthorized",
        402 => "Payment Required",
        403 => "Forbidden",
        404 => "Not Found",
        405 => "Method Not Allowed",
        406 => "Not Acceptable",
        407 => "Proxy Authentication Required",
        408 => "Request Timeout",
        409 => "Conflict",
        410 => "Gone",
        411 => "Length Required",
        412 => "Precondition Failed",
        413 => "Request Entity Too Large",
        414 => "Request URI Too Long",
        415 => "Unsupported Media Type",
        416 => "Requested Range Not Satisfiable",
        417 => "Expectation Failed",
        500 => "Internal Server Error",
        501 => "Method Not Implemented",
        502 => "Bad Gateway",
        503 => "Service Unavailable",
        504 => "Gateway Timeout",
        505 => "HTTP Version Not Supported"
    );
    
    
    /*
    
    Example 
     
    Array
    (
        [Request Method] => GET
        [Request Url] => /
        [Host] => localhost:3000
        [User-Agent] => Mozilla/5.0 (X11; Linux i686; rv:2.0b11) Gecko/20100101 Firefox/4.0b11
        [Accept] => text/html,application/xhtml+xml,application/xml;q=0.9,* / *;q=0.8
        [Accept-Language] => de-de,de;q=0.8,en-us;q=0.5,en;q=0.3
        [Accept-Encoding] => gzip, deflate
        [Accept-Charset] => ISO-8859-1,utf-8;q=0.7,*;q=0.7
        [Keep-Alive] => 115
        [Connection] => keep-alive
        [Cache-Control] => max-age=0
    )
     */
    
    /**
     * 
     * @param \pint\Connection $connection
     * @param string $input
     * @return \pint\Request
     */
    public static function parse(\pint\Connection $connection)
    {
        $instance = new self();
        $input    = $connection->input();

        if(empty($input)) {
            throw new Exception('Empty in input received from connection!');
        }
        
        $raw   = \http_parse_headers($input);
        $lines = \explode("\r\n", $input);
        
        if(!\array_key_exists('Request Method', $raw) ||
           !\array_key_exists('Request Url', $raw)    
        ) {
            return $connection->criticizeSyntax();
        }
        
        $instance->offsetSet('headers', $raw);
        $instance->offsetSet('method',  $raw['Request Method']);
        $instance->offsetSet('uri',     $raw['Request Url']);
        
        return $instance;
    }
    
    /**
     *
     * @return array
     */
    public function headers()
    {
        return $this->offsetGet('headers');
    }
    
    /**
     *
     * @return string
     */
    public function method()
    {
        return $this->offsetGet('method');
    }
    
    /**
     *
     * @return string
     */
    public function version()
    {
        return $this->offsetGet('version');
    }
    
    /**
     *
     * @return string
     */
    public function uri()
    {
        return $this->offsetGet('uri');
    }
    
    /**
     *
     * @return string
     */
    public function statusmsg($code)
    {
        return $this->status[$code];
    }
    
    
// interface methods
    
    /**
     * 
     * @param string $offset
     * @return boolean
     */
    public function offsetExists($offset) 
    {
        return isset($this->container[$offset]);
    }
    
    /**
     * 
     * @param string $offset
     * @return mixed
     */
    public function offsetGet($offset) {
        return $this->container[$offset];
    }
    
    /**
     * 
     * @param string $offset
     * @param mixed $value
     * @return void
     */
    public function offsetSet($offset, $value) 
    {
        $this->container[$offset] = is_string($value) ? trim($value) : $value;
    }
    
    /**
     * 
     * @param string $offset
     * @return void
     */
    public function offsetUnset($offset) 
    {
        unset($this->container[$offset]);
    }
}