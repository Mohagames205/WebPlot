<?php

namespace mohagames\WebPlot\tasks;

use DataLion\PlotSelling\controllers\PlotLinkController;
use mohagames\PlotArea\utils\Plot;
use mohagames\WebPlot\Main;
use pocketmine\scheduler\Task;

class PlotSendTask extends WebTask{
    

    public $domain;
    public $fields;
    
    public function __construct($fields, $config)
    {
        $this->fields = $fields;
        $this->domain = $config["domain"];
    }

    public function onRun()
    {
        $fields = $this->fields;

        $adres = $this->domain;



        $curl = curl_init("$adres/api/plot");
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curl);
        curl_close($curl);
    }

}