<?php
/**
 * Created by JetBrains PhpStorm.
 * User: pvenkatesh
 * Date: 8/3/14
 * Time: 12:03 PM
 * To change this template use File | Settings | File Templates.
 */

Abstract class BaseContainer
{
    public function getJSON()
    {
        return json_encode($this);
    }
}