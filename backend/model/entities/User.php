<?php
include_once('BaseContainer.php');

class User extends  BaseContainer
{
    public $id;
    public $g_id;
    public $create_time;
    public $profile_pic;
    public $email;
    public $name;
    public $g_access_token;
    public $g_refresh_token;

    public function __construct($id,$g_id,$create_time, $profile_pic,$email,$name,$g_access_token,$g_refresh_token)
    {
        $this->id = $id;
        $this->g_id = $g_id;
        $this->create_time = $create_time;
        $this->profile_pic = $profile_pic;
        $this->name = $name;
        $this->email = $email;
        $this->g_access_token = $g_access_token;
        $this->g_refresh_token = $g_refresh_token;
    }

}

