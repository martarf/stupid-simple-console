<?php

namespace PNWPHP\SSC\Service;

class AutoScalingService
{
    private $asg;

    public function __construct(\Aws\Sdk $sdk)
    {
        $this->asg = $sdk->createAutoScaling();
    }

    public function getStatusList(array $grouplist)
    {
        $data = $this->asg->describeAutoScalingGroups();

        $groups = $data['AutoScalingGroups'];

        // TODO: filter based on input
        return array_map(function($g){return $this->processGroup($g);}, $groups);
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
