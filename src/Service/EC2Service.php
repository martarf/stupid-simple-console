<?php
namespace PNWPHP\SSC\Service;

class EC2Service {


	public function getStatusList($servers)
	{

		$responses = json_decode(file_get_contents(__DIR__."/../../config/responses.json"));
        $awsResponse = null;

		foreach($responses as $response) {
		    if($response->name == "describeInstances")
            {
                $awsResponse = $response->response;
                break;
            }
	    }
        if($awsResponse == null || !isset($awsResponse)) {
            throw new \Exception("No Response From AWS");
        }

        $newServers = [];

        foreach($awsResponse->Reservations as $reservation) {
            $newServers[] = array_map($this->processInstance($servers), $reservation->Instances);
//            foreach($reservation->Instances as $instance)
//            {
//                if($server["arn"] == $instance->InstanceId) {
//                    switch($instance->State->Code) {
//                        case 1:
//                            $server["status"] = true;
//                            break;
//                        default:
//                            $server["status"] = false;
//                    }
//
//                    $newServers[] = $server;
//                }
//            }
        }


        return $newServers;

	}

    public function processInstance($instanceArray, $servers)
    {
//        array_filter();
        return $instanceArray;
    }
}