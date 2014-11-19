<?php
include_once('BaseContainer.php');

class User extends  BaseContainer
{
    public $id;
    public $fb_id;
    public $last_name;
    public $first_name;
    public $create_time;
    public $profile_pic;
    public $gender;
    public $hometown_name;
    public $location;
    public $email;
    public $name;
    public $relationship_status;
    public $birthdate;
    public $timezone;

    public function __construct($id,$fb_id,$first_name,$last_name,$create_time, $profile_pic,
                                $gender,$hometown_name,$relationship_status,$birthdate,$email,$name,$location,$timezone)
    {
        $this->id = $id;
        $this->fb_id = $fb_id;
        $this->first_name = $first_name;
        $this->last_name = $last_name;
        $this->create_time = $create_time;
        $this->profile_pic = $profile_pic;
        $this->gender = $gender;
        $this->hometown_name = $hometown_name;
        $this->relationship_status = $relationship_status;
        $this->birthdate = $birthdate;
        $this->name = $name;
        $this->location = $location;
        $this->email = $email;
        $this->timezone = $timezone;
    }

}

