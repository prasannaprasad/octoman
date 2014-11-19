<?php
Abstract Class BaseController
{
    protected $registry;
    function __construct($registry)
    {
        $this->registry = $registry;
    }


}
