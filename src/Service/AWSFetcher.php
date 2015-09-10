<?php

namespace PNWPHP\SSC\Service;

class AWSFetcher
{
    /**
     * @param string $projectName The name of the project
     * @return PNWPHP\SSC\Data\ServerStatus[] List of server statuses
     */
    public function getServerListForProject($projectName)
    {
        $server1 = new \PNWPHP\SSC\Data\ServerStatus("server1",true,true,1);
        $server2 = new \PNWPHP\SSC\Data\ServerStatus("server2",false,false,2);
        $server3 = new \PNWPHP\SSC\Data\ServerStatus("server3",true,true,3);
        $server4 = new \PNWPHP\SSC\Data\ServerStatus("server4",true,true,4);
        return [$server1,$server2,$server3,$server4];
    }
}
