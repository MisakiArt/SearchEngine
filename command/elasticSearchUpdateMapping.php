<?php
require dirname(__FILE__).'/../vendor/autoload.php';
require_once dirname(__FILE__)."/../config.php";

$config = new config();
$elastic_search = $config ->getElasticSearchConfig(md5($config::CODERNAME.date('Y-m-d')));
$hosts = [$elastic_search['ip'].':'.$elastic_search['port']];
$client = Elasticsearch\ClientBuilder::create()->setHosts($hosts)->build();


$params=[
    "index"=>'search_engine_ani',
    "type"=>"search_record",
    "body"=>[
        "properties"=>array(
                    "script"=>array("type"=>"text"),
                    "special_param"=>array("type"=>"text"),
                    "has_special_param"=>array("type"=>"boolean"),
                )
        ]
];
try{
    $res=$client->indices()->putMapping($params);
    print_r($res);
}catch (Exception $e){
    var_dump("Error".$e->getMessage());
}
