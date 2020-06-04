<?php

namespace mohagames\WebPlot\tasks;

use DataLion\PlotSelling\controllers\PlotLinkController;
use mohagames\PlotArea\utils\Plot;
use mohagames\WebPlot\Main;
use pocketmine\scheduler\Task;
use pocketmine\Server;

class PlotUpdateTask extends WebTask {

    public $fields;
    public $domain;
    public $apiToken;

    public function __construct($fields, $config)
    {
        $this->fields = $fields;
        $this->domain = $config["domain"];
        $this->apiToken = $config["apitoken"];

    }


    public function onRun()
    {
        $adres = $this->domain;
        $apitoken = $this->apiToken;

        $fields = $this->fields;

        $plot_name = $fields["plot_name"];
        $plot_members = $fields["plot_members"];
        $plot_owner = $fields["plot_owner"];
        $plot_location = $fields["plot_location"];
        $plot_permissions = $fields["plot_permissions"];
        $plot_max_members = $fields["plot_max_members"];
        $plot_id = $fields["plot_id"];
        $price = $fields["plot_price"];
        $plot_size = $fields["plot_size"];

        

        $curl = curl_init("$adres/api/plot/$plot_id?plot_price=$price&plot_id=$plot_id&plot_owner=$plot_owner&plot_location=$plot_location&plot_permissions=$plot_permissions&plot_max_members=$plot_max_members&plot_name=$plot_name&plot_members=$plot_members&plot_size=$plot_size&api_token=$apitoken");
        curl_setopt($curl, CURLOPT_PUT, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curl);
        curl_close($curl);

        var_dump($response);
    }

}