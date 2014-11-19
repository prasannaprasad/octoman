<?php


include_once __SITE_PATH . '/model/dao/' . 'DBConnection.php';
include_once __SITE_PATH . '/model/entities/' . 'User.php';

define(CLIENT_ID,'231191809666-ncka74mkv68hrgtm5lces6rf30req6b4.apps.googleusercontent.com');
define(CLIENT_SECRET,'yJyaoY6odl8WwqdTkTfDOsmQ');

class UserDao
{

    private function refresh_gtoken($refresh_token)
    {
        $g_url = 'https://accounts.google.com/o/oauth2/token';
        $payload = "refresh_token=$refresh_token&grant_type=refresh_token&client_id=" . CLIENT_ID . "&client_secret=" . CLIENT_SECRET;
        error_log("Refresh payload: $payload");
        $ch = curl_init($g_url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);

        error_log ("Google refresh api response : $response");
        $json = json_decode($response);
        return $json->access_token;
    }

    public function getUserById($user_id)
    {

        $db = DBConnection::getInstance()->getHandle();

        $query = "Select * from Users where g_id='" . $user_id . "'";
        $result = $db->getSingleRecord($query);


        $user = new User($result["id"],$result["g_id"],$result["create_time"],
                        $result["profile_pic"],$result["email"],$result["name"],
                        $result["g_access_token"],$result["g_refresh_token"]);

        $user->g_access_token = $this->refresh_gtoken($user->g_refresh_token);

        $update_query = "UPDATE Users set g_access_token = '" . $user->g_access_token . "' where id = " . $result["id"];
        $db->executeQuery($update_query);
        return $user;
    }

    public function addUser($g_id,$name,$profile_pic,$email,$g_access_token,$g_refresh_token)
    {
        $db = DBConnection::getInstance()->getHandle();

        $prepared_query = " INSERT  into Users(g_id,name,email,create_time,profile_pic,g_access_token,g_refresh_token)
                          values (?,?,?,?,?,?,?)";
        $create_time = date("Y-m-d H:i:s");

        $stmt = $db->getPreparedStatement($prepared_query);
        $stmt->bind_param("sssssss",$g_id,$name,$email, $create_time,$profile_pic,$g_access_token,$g_refresh_token);

        error_log("Executing $prepared_query with params $g_id,$name,$email,$create_time,$profile_pic,$g_access_token,$g_refresh_token");
        if(!($status = $stmt->execute()))
            throw new WebServiceException("Unable to execute query  " ,3017,__FILE__,__LINE__);
        $id = $stmt->insert_id;
        $stmt->close();

        error_log("User $name inserted with $id and status $status");
        return new User($id,$g_id,$create_time,$profile_pic,$email,$name,$g_access_token,$g_refresh_token);
    }

}
