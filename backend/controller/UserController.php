<?php

include_once('model/dao/UserDao.php');
include_once('application/WebServiceException.php');

Class UserController Extends BaseController
{

    private function extractUserid()
    {
        $uri_components = $this->registry->uri_components;
        if(!isset($uri_components[4]))
            throw new WebServiceException(":User id not passed",1212,__FILE__,__LINE__);
        else
            return $uri_components[4];
    }

    public function getUser()
    {
            $user_id = $this->extractUserid();

            error_log("Fetching data for $user_id");

            $userDao = new UserDao();
            $user = $userDao->getUserById($user_id);

            $this->registry->data = $user->getJSON();

    }

    public function addUser()
    {
        $request_body = $this->registry->request_payload;
        $g_id = $this->extractUserid();
        $json_data = json_decode($request_body);
        if(!isset($json_data->name) || $json_data->name == "" ||
            !isset($json_data->g_access_token) || $json_data->g_access_token == "" ||
            !isset($json_data->g_refresh_token) || $json_data->g_refresh_token == "")
             throw new WebServiceException(":Mandatory params in payload missing",1218,__FILE__,__LINE__);
        $userDao = new UserDao();
        $user = $userDao->addUser($g_id,$json_data->name,$json_data->profile_pic,$json_data->email,$json_data->g_access_token,
                                $json_data->g_refresh_token);
        $this->registry->data = $user->getJSON();
    }

}
