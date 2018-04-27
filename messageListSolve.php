<?php
require_once "model/phpQuery.php";
require_once "model/spider.php";
require_once "config.php";
require 'vendor/autoload.php';

$spider = new spider();
$spider->setModifyDate();
$config = new config();
$elastic_search = $config ->getElasticSearchConfig(md5($config::CODERNAME.$spider->modify_date));
$redis_config = $config ->getRedisConfig(md5($config::CODERNAME.$spider->modify_date));
$site_config = $config->getTargetSiteConfig();

$hosts = [$elastic_search['ip'].':'.$elastic_search['port']];
$client = Elasticsearch\ClientBuilder::create()->setHosts($hosts)->build();
$bulk=['index'=>'search_engine_ani','type'=>'search_record','body'=>[]];
$redis = new redis();
$redis ->connect($redis_config['ip'],$redis_config['port']);
while(true){
    $count = count($bulk['body']);
    $params=$redis->brPop('MiProjectUrlList',5);
    if(!empty($params[1])){
        spider::getInformationByUrl($params[1],$site_config['target_site_referer'],$bulk['body']);
    }else{
        if(!empty($bulk['body'])){
            $res=$client->bulk($bulk);
            print_r($res);
            $bulk=[];
            $bulk=['index'=>'search_engine_ani','type'=>'search_record','body'=>[]];
        }
    }
    if(count($bulk['body'])>20){
       $res=$client->bulk($bulk);
       print_r($res);
        $bulk=[];
        $bulk=['index'=>'search_engine_ani','type'=>'search_record','body'=>[]];
    }
}