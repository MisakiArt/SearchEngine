<?php
require '../vendor/autoload.php';

$hosts = array('127.0.0.1:9200');
$client = Elasticsearch\ClientBuilder::create()->setHosts($hosts)->build();
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
                    "title"=>array("type"=>"text","index"=>"analyzed"),//分词检索
                    "hot"=>array("type"=>"long","index"=>"not_analyzed"),//用于统计
                    "tag"=>array("type"=>"keyword","index"=>"not_analyzed"),//用于统计
                    "source"=>array("type"=>"text","index"=>"not_analyzed")
                )
            ],
            'all_web_site_record'=>[
                "properties"=>array(
                    "id"=>array("type"=>"long"),
                    "web_url"=>array("type"=>"text","index"=>"not_analyzed"),//用于统计
                    "web_title"=>array("type"=>"keyword","index"=>"analyzed"),//分词检索
                    "child_number"=>array("type"=>"long","index"=>"not_analyzed"),//用于统计
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
