<?php
namespace PNWPHP\SSC\Service;

class EC2Service {


	public function getStatusList(&$servers)
	{
		$responses = json_decode(file_get_contents(__DIR__."/../../config/responses.json"));

		//var_dump($responses);

        return [];

	}
}
