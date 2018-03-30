<?php
require '../vendor/autoload.php';

$hosts = array('127.0.0.1:9200');
$client = Elasticsearch\ClientBuilder::create()->setHosts($hosts)->build();

$bulk=['index'=>'search_engine_ani','type'=>'all_web_site_record','id'=>'dlidli'];
$bulk['body'][]=['web_url'=>'http://www.dilidili.wang/','web_title'=>'D站'];
try{
    $res=$client->create($bulk);
    print_r($res);
}catch (Exception $e){
    var_dump("Error".$e->getMessage());
}
