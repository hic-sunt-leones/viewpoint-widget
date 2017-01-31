<?php

namespace Leones;

class Dummy
{   

    function __construct($settings) {
       $this->settings = $settings;
    }

    // vars
    public $settings = array();
    public $project = array();


    // methods
    public function getProjectByUUID($uuid) {
        $url = $this->settings['apiUrl'] . "projects/uuid/" . $uuid; 
                
        $ch = curl_init();  
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch,CURLOPT_HEADER, "Accept:application/json"); 
        //curl_setopt($ch,CURLOPT_HEADER, "Authorization:Bearer $token"); 
        //curl_setopt($ch,CURLOPT_POSTFIELDS, $postData);    
        $output=curl_exec($ch);
        curl_close($ch);

        $data = json_decode($output,true);

        print_r($output);
        //die($url);

        if(isset($data['project'])){
            $this->session->set_userdata(array("project"=>$data['project']));
            return $_SESSION['project'];
        }else{
            echo $data['message'];
            die();
        }

        return $results;
    }
}