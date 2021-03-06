<?php

namespace pint\Middleware;

use pint\Middleware\MiddlewareAbstract;

class Logging extends MiddlewareAbstract
{
    /**
     *
     * @param array $env
     * @return mixed 
     */
    function call($env = array(), array $response = null)
    {
        echo "\n" . get_class($this) . ": request for " . $env["REQUEST_URI"] . "\n";
        
        return $this->next($env);
    }
}
