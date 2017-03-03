<?php

namespace Leones;


use Monolog\Logger;


/**
 * Class VolksMapper
 * Base class for shared functionality
 */
class VolksMapper
{

    // vars
    protected $settings = array();

    /** @var  integer The API error code */
    protected $errorCode;

    /**
     * @var Logger
     */
    private $logger;

    public function __construct($settings, Logger $logger)
    {
        $this->settings = $settings;
        $this->logger = $logger;
    }

    /**
     * Get a project form the API
     *
     * @param string $uuid
     * @return array|bool
     */
    public function getProjectByUUID($uuid)
    {
        $url = $this->settings['apiUrl'] . "projects/uuid/" . $uuid;
        $output = $this->getFromAPI($url);

        $data = json_decode($output, true);

        if (isset($data['project'])) {
            $data['project']['uuid'] = $uuid;

            return $data['project'];
        }

        return false;
    }

    /**
     * Wrapper for API calls that perform a GET request
     *
     * @param string $url
     * @return mixed
     */
    protected function getFromAPI($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, "Accept:application/json");
        return $this->doCurlCall($ch);
    }

    /**
     * POST user data to the API to receive a valid user token
     *
     * @param string $name
     * @param string $pass
     * @return bool
     */
    public function getToken($name, $pass)
    {
        $url = $this->settings['apiUrl'] . "authenticate";
        $postData = array("_username" => $name, "_password" => $pass);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, "Accept:application/json");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

        $output = $this->doCurlCall($ch);
        $data = json_decode($output, true);

        if (isset($data['token'])) {
            return $data['token'];
        }

        return false;
    }


    /**
     * Get details for the user identified by the token
     *
     * @param string $token
     * @return bool|array
     */
    public function getUser($token)
    {
        $url = $this->settings['apiUrl'] . "people/me";
        $curl = $this->PostToAPI($url, [], $token);
        $data = json_decode($curl, true);

        if ($data['user']) {
            return $data['user'];
        }

        return false;
    }


    public function getTask($token, $projectId)
    {
        $url = $this->settings['apiUrl'] . "tasks/next";
        $postData = array("task" => array("projectId" => $projectId));

        $curl = $this->PostToAPI($url, $postData, $token);

        $data = json_decode($curl, true);
        if ($data['task']) {
            return $data['task'];
        }

        return false;
    }


    /**
     *
     * @param string $uuid
     * @return bool
     */
    public function demoTask($uuid)
    {
        $url = $this->settings['apiUrl'] . "projects/uuid/" . $uuid . "/demo";
        $output = $this->getFromAPI($url);

        $data = json_decode($output, true);
        if ($data['task']) {
            return $data['task'];
        }

        return false;
    }


    /**
     * Skip task
     *
     * @param integer $itemId
     * @param integer $projectId
     * @param string $token
     * @return bool
     */
    public function skipTask($itemId, $projectId, $token)
    {
        $url = $this->settings['apiUrl'] . "tasks/skip";
        $postData = array(
            "task" => array(
                "projectId" => $projectId,
                "itemId"    => $itemId
            )
        );

        $curl = $this->PostToAPI($url, $postData, $token);
        if ($curl) {
            return true;
        }

        return false;
    }


    protected function PostToAPI($url, $postData = array(), $token = '')
    {
        if (empty($postData)) {
            $data_string = "";
        } else {
            $data_string = json_encode($postData);
        }

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data_string),
                'Authorization: Bearer ' . $token
            )
        );

        return $this->doCurlCall($ch);
    }

    protected function doCurlCall ($ch)
    {
        $output = curl_exec($ch);
        if ($output === false) {
            $this->logger->addError('CURL error: ' . curl_error($ch));
            return false;
        }

        $info = curl_getinfo($ch);
        curl_close($ch);
        //$this->logger->addError('CURL answer: ' . $info['http_code']);
        if ($info['http_code'] === 200) {
            return $output;
        }

        $this->logger->addError('CURL answered with error: ' . print_r($info, 1));
        $this->errorCode = $info['http_code'];
        return false;
    }

    /**
     * @return integer
     */
    public function getErrorCode()
    {
        return $this->errorCode;
    }
}
