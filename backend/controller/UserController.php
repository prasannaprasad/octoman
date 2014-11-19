<?php

include_once('model/dao/UserDao.php');
include_once('model/dao/UserFriendsDao.php');
include_once('model/dao/StampCloudDao.php');
include_once('model/dao/StampDao.php');
include_once('application/WebServiceException.php');
require_once __SITE_PATH."/Facebook_extractor/FBDataExtractor/Utils/Utils.php";
use FBDataExtractor\Utils\Utils;

Class UserController Extends BaseController
{
    public function getUsersTaggedWith(){
        $fb_id = $this->extractUserid();
        $noun = Utils::getValueSafelyArr($this->registry->query_params,'noun');
        if($noun == '' || $fb_id == ''){
            throw new WebServiceException("Can't search without noun param",2001,__FILE__,__LINE__);
        }

        $userDao = new UserDao();
        $users = $userDao->getUsersTaggedWith($noun, $fb_id);
        return $this->registry->data = json_encode($users);
    }
    
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

    public function getUserFriends()
    {
        $user_id = $this->extractUserid();

        error_log("Fetching friends for $user_id ");

        $userFriendsDao = new UserFriendsDao();
        $mini_users = $userFriendsDao->getUserFriends($user_id);;

        $this->registry->data = json_encode($mini_users);
    }

    public function getUserStampCloud()
    {
        $user_id = $this->extractUserid();
        $offset = $this->registry->query_params["offset"];
        if(!isset($offset) || $offset == "") $offset = 0;

        $limit = $this->registry->query_params["limit"];
        if(!isset($limit) || $limit == "") $limit = 20;

        $fetch_users = $this->registry->query_params["fetch_users"];
        if(!isset($fetch_users) || $fetch_users == "" || $fetch_users == "false")
            $fetch_users = false;
        else $fetch_users = true;

        error_log("Fetching stampcloud for $user_id with offset $offset and limit $limit");
        $stamp_cloud_dao = new StampCloudDao();
        $stamp_cloud = $stamp_cloud_dao->getUserStampCloud($user_id,$offset,$limit,$fetch_users);

        $this->registry->data = $stamp_cloud->getJSON();

    }

    public function getUserProfileFeed()
    {
        $user_id = $this->extractUserid();
        $offset = $this->registry->query_params["offset"];
        if(!isset($offset) || $offset == "") $offset = 0;

        $limit = $this->registry->query_params["limit"];
        if(!isset($limit) || $limit == "") $limit = 20;

        error_log("Fetching user profile feed for $user_id with offset $offset and limit $limit" );

        $stampDao = new StampDao();
        $stamps = $stampDao->getUserProfileFeed($user_id,$offset,$limit);

        $this->registry->data = json_encode($stamps);

    }

    public function getUserHomeFeed()
    {
        $user_id = $this->extractUserid();
        $offset = $this->registry->query_params["offset"];
        if(!isset($offset) || $offset == "") $offset = 0;

        $limit = $this->registry->query_params["limit"];
        if(!isset($limit) || $limit == "") $limit = 20;

        error_log("Fetching user home feed for $user_id with offset $offset and limit $limit" );

        $stampDao = new StampDao();
        $stamps = $stampDao->getUserHomeFeed($user_id,$offset,$limit);

        $this->registry->data = json_encode($stamps);

    }

}
