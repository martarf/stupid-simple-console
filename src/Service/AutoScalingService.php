<?php

namespace PNWPHP\SSC\Service;

class AutoScalingService
{
    public function getStatusList(array $grouplist)
    {
        $data = json_decode(file_get_contents(dirname(dirname(__DIR__)) . '/config/responses.json'), true);

        $response = null;
        foreach($data as $rsp) {
            if($rsp['name'] === 'describeAutoScalingGroups') {
                $response = $rsp['response']['AutoScalingGroups'];
            }
        }

        if($response === null) {
            throw new \Exception('Could not find fake data for server groups.');
        }

        // TODO: filter based on input
        return array_map(function($g){return $this->processGroup($g);}, $response);
    }

    private function processGroup($group)
    {
        $instances = $group['Instances'];
        $healthyInstances = array_filter($instances, function($i) {
            return
                $i['HealthStatus'] === 'Healthy' &&
                $i['LifecycleState'] === 'InService';
        });

        return [
            'name' => 'someFakeName',
            'type' => 'group',
            'arn' => $group['AutoScalingGroupARN'],
            'status' => count($healthyInstances) > 0,
            'count' => count($healthyInstances),
        ];
    }
}
