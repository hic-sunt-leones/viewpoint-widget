<?php

namespace Leones;

class Volksmapper
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

        //print_r($output);
        //die($url);

        if(isset($data['project'])){
            $data['project']['uuid'] = $uuid;
            return $data['project'];
        }else{
            return false;
        }
    }

    public function getToken($name,$pass){
        
        
        $url = $this->settings['apiUrl'] . "authenticate";
        $postData = array("_username"=>$name,"_password"=>$pass);
       
        $ch = curl_init();  
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch,CURLOPT_HEADER, "Accept:application/json"); 
        //curl_setopt($ch,CURLOPT_HEADER, "Authorization:Bearer $token"); 
        curl_setopt($ch,CURLOPT_POSTFIELDS, $postData);    
        $output=curl_exec($ch);
        curl_close($ch);

        $data = json_decode($output,true);

        if(isset($data['token'])){
            return $data['token'];
        }else{
            return false;
        }


    }

    public function getUser($token){
        
        $url = $this->settings['apiUrl'] . "people/me";
       
        $ch = curl_init($url);  
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: 0',
                'Authorization: Bearer ' . $_SESSION['token']
            )
        );
        $output = curl_exec($ch);
        if($output === false){
            //echo 'Curl error: ' . curl_error($ch);
        }else{
            //echo 'Operation completed without any errors';
        }
        curl_close($ch);

        $data = json_decode($output,true);
        
        if(isset($data['user'])){
            return $data['user'];
        }else{
            return false;
        }


    }


    public function getTask(){
        
        $url = $this->settings['apiUrl'] . "tasks/next";
        $postData = array("task"=> array("projectId"=>$_SESSION['project']['id']));
        $data_string = json_encode($postData);

        $ch = curl_init($url);  
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data_string),
                'Authorization: Bearer ' . $_SESSION['token']
            )
        );
        $output = curl_exec($ch);
        if($output === false){
            //echo 'Curl error: ' . curl_error($ch);
        }else{
            //echo 'Operation completed without any errors';
        }
        curl_close($ch);
        //print_r($output);
        
        $data = json_decode($output,true);
        
        if(isset($data['task'])){
            return $data['task'];
        }else{
            return false;
        }


    }


    public function saveTask($postdata){
        
        $url = $this->settings['apiUrl'] . "tasks/save";
        $postData = array(
                        "task"=> array(
                                    "projectId"=>$_SESSION['project']['id'],
                                    "itemId"=>$postdata['itemId'],
                                    "data"=>array(
                                                "target" => json_decode($postdata['targetPoint']),
                                                "camera" => json_decode($postdata['cameraPoint']),
                                                "geojson" => json_decode($postdata['fieldOfView'])
                                            )
                                )
                        );
        $data_string = json_encode($postData);

        print_r($postData);
        $ch = curl_init($url);  
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data_string),
                'Authorization: Bearer ' . $_SESSION['token']
            )
        );
        $output = curl_exec($ch);
        if($output === false){
            //echo 'Curl error: ' . curl_error($ch);
        }else{
            //echo 'Operation completed without any errors';
        }
        $info = curl_getinfo($ch);

        curl_close($ch);
        //print_r($info);
        
        $data = json_decode($output,true);
        
        if($info['http_code']===200){
            return true;
        }else{
            return false;
        }


    }


    public function skipTask($itemId){
        
        $url = $this->settings['apiUrl'] . "tasks/skip";
        $postData = array(
                        "task"=> array(
                                    "projectId"=>$_SESSION['project']['id'],
                                    "itemId"=>$itemId
                                )
                        );
        $data_string = json_encode($postData);

        $ch = curl_init($url);  
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data_string),
                'Authorization: Bearer ' . $_SESSION['token']
            )
        );
        $output = curl_exec($ch);
        if($output === false){
            echo 'Curl error: ' . curl_error($ch);
        }else{
            echo 'Operation completed without any errors';
        }
        $info = curl_getinfo($ch);

        curl_close($ch);
        print_r($info);
        
        // TODO: CHECK IF API WAS OK WITH OUR LATEST REQUEST

        $data = json_decode($output,true);
        
        if($info['http_code']===200){
            return true;
        }else{
            return false;
        }


    }
}