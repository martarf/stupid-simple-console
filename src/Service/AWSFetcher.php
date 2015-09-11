<?php

namespace PNWPHP\SSC\Service;

use PNWPHP\SSC\Data\ServerStatus;

class AWSFetcher
{
    private $pdo;
    private $ec2;

    public function __construct(\PDO $pdo, EC2Service $ec2)
    {
        $this->pdo = $pdo;
        $this->ec2 = $ec2;
    }

    /**
     * @param string $projectName The name of the project
     * @return PNWPHP\SSC\Data\ServerStatus[] List of server statuses
     */
    public function getServerListForProject($projectId)
    {
        $sql =
            'SELECT `Server`.`Server_Name` as `name`, `Server`.`ARN` as `arn`, `Server`.`Type` as `type` ' .
            'FROM `Server` WHERE `Server`.`arn` in ' .
            '(SELECT `Project_Server`.`arn` FROM `Project_Server` WHERE `Project_Server`.`Project_ID` = :pid)';

//        try{
//            $query = $this->pdo->prepare($sql);
//            $query->execute(['pid' => $projectId]);
//            $data = $query->fetchAll(\PDO::FETCH_COLUMN);
//        } catch(\PDOException $e) {
//            // TODO: Fail safe
//            throw $e;
//        }
//
//        $singleServers = array_filter($data, function($s) {
//            return $s['type'] === 'single';
//        });
//
//        $groupServers = array_filter($data, function($s) {
//            return $s['type'] === 'group';
//        });

        $singleServers = [[
            "arn" => "abcde",
            "name" => "test server",
            "type" => 1
        ]];
        $groupServers = [];
        $groupStatus = [];

        $singleStatus = $this->ec2->getStatusList($singleServers);
//        $groupStatus = $this->asg->getStatusList([]);

        $singleStatus = array_map(function($status) {
             return new ServerStatus($status['name'], $status['status'], false, 1);
        }, $singleStatus);

//        $groupStatus = array_map(function($status) {
//             return new ServerStatus($status['name'], $status['status'], true, $status['count']);
//        }, $singleStatus);

        return array_merge($singleStatus, $groupStatus);
    }
}
