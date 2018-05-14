<?php
class SdkApiController
{
    private $api_url = "http://localhost";
    private $api_auth_url = "http://localhost/oauth/v2/token";
    private $api_user = "thomas";
    private $api_password = "admin";
    private $grant_type = "password";
    private $client_id = "1_15e7tdk08wao8gsw4g8ssoc04skc0o4c44gw04w4sk488888og";
    private $client_secret = "3yygqjselgg0s8ggok8ks0s4o8cg80oss4sskkc8sc0wksc04g";
    private $client_name = "Backoffice";

    private $access_token;
    private $refresh_token;

    public function __construct( $client_id, $client_secret )
    {
        $this->setClientId( $client_id );
        $this->setCientSecret( $client_secret );
        $this->prepareAccessToken();
    }

    private function setClientId( $client_id ){
        $this->client_id = $client_id;
    }

    private function setCientSecret( $client_secret ){
        $this->client_secret = $client_secret;
    }

    private function prepareAccessToken()
    {
        $command = "curl -XPOST -d 'username=".$this->api_user."&password=".$this->api_password."&grant_type=".$this->grant_type."&client_id=".$this->client_id."&client_secret=".$this->client_secret."&client_name=".$this->client_name."' '".$this->api_auth_url."'";
        $file = exec ( $command );
        $json_file = json_decode($file);
        $this->access_token = $json_file->{'access_token'};
        $this->refresh_token = $json_file->{'refresh_token'};
    }


    /**
     * @return mixed|\Psr\Http\Message\ResponseInterface - - - show all users
     *
     */

    public function getApiUsers(  )
    {
        $url = $this->api_url."/users";

        $command = 'curl -H "Authorization: Bearer '.$this->access_token.'" '.$url.'/users';

        exec($command);
    }

    /**
     * @return mixed - - - create a user
     *
     */

    public function postApiUsers( $user, $email, $password ){

        $json_file = "'{\"username\": \"$user\",\"email\": \"$email\",\"plain_password\": \"$password\"}'";

        $url = $this->api_url."/users";

        $command = "curl -X POST -H 'Content-Type: application/json' ".$url."?access_token=".$this->access_token." \
                        -d $json_file";

        exec($command);
    }

    /**
     * @param $id - - - write a user by id
     *
     */

    public function putApiUsers( $id )
    {
        $json_file = "'{\"id\":\"$id\",\"username\": \"OOOOrossi\",\"email\": \"admin1@rassloff.info\",\"plain_password\": \"asdwerdfwerf\"}'";

        $url = $this->api_url."/users/".$id."?access_token=".$this->access_token;

        $command = "curl -X PUT -H \"Content-Type: application/json\" $url -d $json_file";

        exec($command);
    }

    /**
     * @param $id - - - delete a user by id
     *
     */

    public function deleteApiUsers( $id )
    {
        $url = $this->api_url."/users/".$id."?access_token=".$this->access_token;
        $command = "curl -X DELETE -H \"Content-Type: application/json\" $url ";
        exec($command);
    }

    /**
     * @param $username
     * @param $password
     *                      - - - to login as user
     */
    /** it isnt working !? */
    public function loginApiUsers( $username, $password ){
        $command = "curl -X GET -i -H \"Content-type: application/json\"  $this->api_auth_url -d '{
            \"grant_type:\": \"client_credentials\",
            \"username\": \"$username\",
            \"password\": \"$password\"
        }' ";
        $result = exec($command);
        echo $command;
        return $result;
    }
}
?>
