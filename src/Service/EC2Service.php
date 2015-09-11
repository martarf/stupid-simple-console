<?php
namespace PNWPHP\SSC\Service;

class EC2Service {


	public function getStatusList($servers)
    {

        $data = json_decode(file_get_contents(__DIR__ . "/../../config/responses.json"), true);
        $response = null;

        foreach ($data as $rsp) {
            if ($rsp['name'] == "describeInstances") {
                $response = $rsp['response']['Reservations'];
                break;
            }
        }
        if ($response == null) {
            throw new \Exception('Could not find fake data for servers.');
        }

       $instances = [];
        foreach($response as $reservation) {
            foreach($reservation["Instances"] as $instance)
            {
                $instances[] = $this->processInstance($instance);
            }
        }
        
        return $instances;

    }


    public function processInstance($instance)
    {
        return [
            'name' => 'someFakeName',
            'type' => 'single',
            'arn' => $instance['InstanceId'],
            'status' => $instance["State"]["Code"] == 0,
            'count' => 1
        ];
        
    }
}
