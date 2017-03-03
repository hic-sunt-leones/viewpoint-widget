<?php

namespace Leones;


class ViewpointVolksMapper extends VolksMapper
{

    /**
     * Custom save for this widget
     *
     * @param array $postdata
     * @param $projectId
     * @param string $token
     * @return bool
     */
    public function saveTask($postdata, $projectId, $token)
    {
        $url = $this->settings['apiUrl'] . "tasks/save";
        $postData = array(
            "task" => array(
                "projectId" => $projectId,
                "itemId"    => $postdata['itemId'],
                "data"      => array(
                    "target"  => json_decode($postdata['targetPoint']),
                    "camera"  => json_decode($postdata['cameraPoint']),
                    "geojson" => json_decode($postdata['fieldOfView'])
                )
            )
        );

        $curl = $this->PostToAPI($url, $postData, $token);
        if ($curl) {
            return true;
        }

        return false;
    }


    /**
     * Use either a specific lat lon for record or a default
     *
     * @param array $task
     * @return array
     */
    public function getMapLatLon($task)
    {
        if(empty($task['item']['location'])){ // specific latlon for record?
            $mapLatLon = explode(",",$task['mapLatLon']);
        }else{
            $mapLatLon = explode(",",$task['item']['location']);
            $task['mapLatLon'] = $task['item']['location'];
        }
        $task['mapLonLat'] = trim($mapLatLon[1]) . ", " . trim($mapLatLon[0]);
        return $task;
    }
}