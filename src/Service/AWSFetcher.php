<?php

namespace PNWPHP\SSC\Service;

use PNWPHP\SSC\Data\ServerStatus;

class AWSFetcher
{
    private $pdo;
    private $ec2;
    private $asg;

    public function __construct(\PDO $pdo, EC2Service $ec2, AutoScalingService $asg)
    {
        $this->pdo = $pdo;
        $this->ec2 = $ec2;
        $this->asg = $asg;
    }

    /**
     * @param string $projectName The name of the project
     * @return PNWPHP\SSC\Data\ServerStatus[] List of server statuses
     */
    public function getServerListForProject($projectId)
    {
        $sql =
            'SELECT * FROM servers WHERE servers.arn in '.
            '( SELECT arn FROM project_server WHERE project_id = :pid )';

        try{
            $query = $this->pdo->prepare($sql);
            $query->execute(['pid' => $projectId]);
            $data = $query->fetchAll(\PDO::FETCH_ASSOC);
        } catch(\PDOException $e) {
            // TODO: Fail safe
            throw $e;
        }

        $singleServers = array_filter($data, function($s) {
            return $s['type'] === 'single';
        });
        $groupServers = array_filter($data, function($s) {
            return $s['type'] === 'group';
        });

        $singleStatus = $this->ec2->getStatusList($singleServers);
        $groupStatus = $this->asg->getStatusList($groupServers);

        $singleStatus = array_map(function($status) {
            return new ServerStatus($status['name'], $status['status'], false, 1);
        }, $singleStatus);

        $groupStatus = array_map(function($status) {
            return new ServerStatus($status['name'], $status['status'], true, $status['count']);
        }, $groupStatus);

        return array_merge($singleStatus, $groupStatus);
    }
}
