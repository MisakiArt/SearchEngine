<?php
require dirname(__FILE__).'/../vendor/autoload.php';
require_once dirname(__FILE__)."/../config.php";

$config = new config();
$elastic_search = $config ->getElasticSearchConfig(md5($config::CODERNAME.date('Y-m-d')));
$hosts = [$elastic_search['ip'].':'.$elastic_search['port']];
$client = Elasticsearch\ClientBuilder::create()->setHosts($hosts)->build();
$deleteParams['index'] = 'search_engine_ani';
$res = $client->indices()->getMapping($deleteParams);
//$client->indices()->delete($deleteParams);
$params = [
    'index'=>'search_engine_ani',
    "body"=>[
        "settings"=>["number_of_shards"=>5,"number_of_replicas"=>0],
        "mappings"=>[
            "search_record"=>[
                "_parent"=>[
                    "type"=>'all_web_site_record'
                ],
                "properties"=>array(
                    "web_url"=>array("type"=>"text","index"=>"not_analyzed"),//用于统计
                    "title"=>array("type"=>"text","index"=>"analyzed","boost"=>2),//分词检索
                    "hot"=>array("type"=>"long","index"=>"not_analyzed"),//用于统计
                    "tag"=>array("type"=>"keyword","index"=>"not_analyzed"),//用于统计
                    "content"=>array("type"=>"text","index"=>"analyzed"),//分词检索
                    "source"=>array("type"=>"text","index"=>"not_analyzed"),
                    "create_time"=>array("type"=>"date"),
                    "update_time"=>array("type"=>"date"),
                )
            ],
            'all_web_site_record'=>[
                "properties"=>array(
                    "id"=>array("type"=>"long"),
                    "web_url"=>array("type"=>"text","index"=>"not_analyzed"),//用于统计
                    "web_title"=>array("type"=>"keyword","index"=>"analyzed"),//分词检索
                    "create_time"=>array("type"=>"date"),
                    "update_time"=>array("type"=>"date"),
                )
            ]
        ]
    ]
];
try{
    $resp = $client->indices()->create($params);
    print_r($resp);
}catch (Exception $e){
    var_dump("Error: ".$e->getMessage());
}
