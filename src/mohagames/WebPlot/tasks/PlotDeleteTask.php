<?php

namespace mohagames\WebPlot\tasks;

use mohagames\WebPlot\Main;
use pocketmine\scheduler\AsyncTask;
use pocketmine\scheduler\Task;

class PlotDeleteTask extends WebTask {


    public $plotId;
    public $domain;
    public $apiToken;

    public function __construct(int $plotId, $config)
    {
        $this->plotId = $plotId;
        $this->domain = $config["domain"];
        $this->apiToken = $config["apitoken"];
    }


    public function onRun()
    {
        $adres = $this->domain;
        $apitoken = $this->apiToken;

        $plotId = $this->plotId;

        $curl = curl_init("$adres/api/plot/$plotId?api_token=$apitoken");
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curl);
        curl_close($curl);

        var_dump($response);
    }


}