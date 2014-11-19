<?php

include_once('WebServiceException.php');

class Router
{
    private $registry;
    private $path;
    private $args = array();
    public $file;
    public $controller;
    public $action;
    function __construct($registry)
    {
        $this->registry = $registry;
    }

    function setPath($path)
    {

        $this->path = $path;

        error_log("### in router set path: $path \n");
    }

    public function loader()
    {
        error_log("## In Router loader ########");
        $this->getController();

        $class = $this->controller . 'Controller';
        error_log("File: $this->file \n Class: $class \n Action: $this->action \n");
        #error_log("Trying readability on $this->file");
        if (is_readable($this->file) == false)
        {
            throw new WebServiceException ("$this->file not readable", 1110, __FILE__, __LINE__);
        }
        include ($this->file);

        $controller = new $class($this->registry);
        

        if (is_callable(array($controller, $this->action)) == false)
        {
            throw new WebServiceException ("Action $this->action  not available", 1111,__FILE__,__LINE__);
        }
        else
        {
            $action = $this->action;
        }

        $controller->$action();
    }

    private function getController()
    {
        error_log('In getController:');
        $uri_path = $_SERVER['REQUEST_URI'];
        $request_method = $_SERVER['REQUEST_METHOD'];

        $this->registry->uri_path = $uri_path;
        $this->registry->request_method = $request_method;
        $uri_components = explode('/',$uri_path);
        $this->registry->uri_components = $uri_components;

        error_log(print_r($uri_components,true));

        if($request_method == 'POST')
        {
            $this->registry->request_payload = @file_get_contents('php://input');
            error_log("Request payload:" . $this->registry->request_payload);
        }
        else if($request_method == 'GET')
        {
            $this->registry->query_params = $_GET;
        }

        $uri_components[3] = preg_replace("/\?.*/","",$uri_components[3]); // extract uri path minus params
        switch($uri_components[3])
        {
            case "user":
                $this->controller = 'User';
                $uri_components[4] = preg_replace("/\?.*/","",$uri_components[4]);
                $uri_components[5] = preg_replace("/\?.*/","",$uri_components[5]);

                if ($request_method == 'POST')
                    $this->action = 'addUser';
                else
                    $this->action = 'getUser';
                break;
            default:
                error_log("Unsupported controller ");
                throw new WebServiceException ("Action $this->action  not available", 1111,__FILE__,__LINE__);

        }


        $this->file = $this->path .'/'. $this->controller . 'Controller.php';

    }



}

