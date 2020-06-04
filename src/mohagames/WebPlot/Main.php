<?php

namespace mohagames\WebPlot;

use DataLion\PlotSelling\controllers\PlotLinkController;
use DataLion\PlotSelling\events\PlotPriceUpdateEvent;
use mohagames\PlotArea\events\PlotAddMemberEvent;
use mohagames\PlotArea\events\PlotCreateEvent;
use mohagames\PlotArea\events\PlotDeleteEvent;
use mohagames\PlotArea\events\PlotRemoveMemberEvent;
use mohagames\PlotArea\events\PlotSetOwnerEvent;
use mohagames\PlotArea\utils\Plot;
use mohagames\WebPlot\tasks\PlotDeleteTask;
use mohagames\WebPlot\tasks\PlotSendTask;
use mohagames\WebPlot\tasks\PlotUpdateTask;
use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class Main extends PluginBase implements Listener{
        
    
    public static $config;

    public function onEnable()
    {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);

        $defaults = [
            "apitoken" => "token",
            "domain" => "127.0.0.1"
        ];

        $config = new Config($this->getDataFolder() . "config.yml", Config::YAML, $defaults);
        $config->save();
        
        self::$config = $config;

        $this->sendAllPlots();
        
        
    }


    public function sendAllPlots(){
        $plots = Plot::getPlots();

        foreach($plots as $plot){
            $this->sendPlot($plot);
        }
    }

    public function onPlotCreation(PlotCreateEvent $e)
    {
        $this->sendPlot($e->getPlot());
    }

    public function sendPlot(Plot $plot){
        $config = $this->getConfig();
        $fields = [
            "plot_id" => $plot->getId(),
            "plot_owner" => $plot->getOwner(),
            "plot_location" => json_encode($plot->getLocation()->getArrayedLocation()),
            "plot_permissions" => json_encode($plot->getPermissions()),
            "plot_max_members" => $plot->getMaxMembers(),
            "plot_name" => $plot->getName(),
            "plot_members" => json_encode($plot->getMembers()),
            "plot_price" => PlotLinkController::getPriceByPlot($plot->getId()),
            "plot_size" => json_encode($plot->getSize()),
            "api_token" => $config->get("apitoken")
        ];


        $configArray = ["domain" => $config->get("domain")];

        $this->getServer()->getAsyncPool()->submitTask(new PlotSendTask($fields, $configArray));
    }

    public function onPlotDeletion(PlotDeleteEvent $e){
        $config = $this->getConfig();
        $configArray = ["domain" => $config->get("domain"), "apitoken" => $config->get("apitoken")];
        $this->getServer()->getAsyncPool()->submitTask(new PlotDeleteTask($e->getPlot()->getId(), $configArray));
    }

    public function onSetOwner(PlotSetOwnerEvent $e){
        $config = $this->getConfig();
        $plot = $e->getPlot();

        $fields = [
            "plot_id" => $plot->getId(),
            "plot_owner" => $e->getOwner(),
            "plot_location" => json_encode($plot->getLocation()->getArrayedLocation()),
            "plot_permissions" => json_encode($plot->getPermissions()),
            "plot_max_members" => $plot->getMaxMembers(),
            "plot_name" => $plot->getName(),
            "plot_members" => json_encode($plot->getMembers()),
            "plot_price" => PlotLinkController::getPriceByPlot($plot->getId()),
            "plot_size" => json_encode($plot->getSize()),
        ];

        $configArray = ["domain" => $config->get("domain"), "apitoken" => $config->get("apitoken")];
        $this->getServer()->getAsyncPool()->submitTask(new PlotUpdateTask($fields, $configArray));
    }

    public function onAddMember(PlotAddMemberEvent $e){
        $this->updatePlot($e->getPlot());
    }

    public function onRemoveMember(PlotRemoveMemberEvent $e){
        $this->updatePlot($e->getPlot());
    }

    public function onPriceUpdate(PlotPriceUpdateEvent $e){
        $this->updatePlot($e->getPlot());
    }

    public function updatePlot(Plot $plot){
        $config = $this->getConfig();

        $fields = [
            "plot_id" => $plot->getId(),
            "plot_owner" => $plot->getOwner(),
            "plot_location" => json_encode($plot->getLocation()->getArrayedLocation()),
            "plot_permissions" => json_encode($plot->getPermissions()),
            "plot_max_members" => $plot->getMaxMembers(),
            "plot_name" => $plot->getName(),
            "plot_members" => json_encode($plot->getMembers()),
            "plot_price" => PlotLinkController::getPriceByPlot($plot->getId()),
            "plot_size" => json_encode($plot->getSize()),
        ];

        $configArray = ["domain" => $config->get("domain"), "apitoken" => $config->get("apitoken")];
        $this->getServer()->getAsyncPool()->submitTask(new PlotUpdateTask($fields, $configArray));
    }
    


}