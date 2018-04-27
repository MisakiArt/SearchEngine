<?php
require '../vendor/autoload.php';

$hosts = array('127.0.0.1:9200');
$client = Elasticsearch\ClientBuilder::create()->setHosts($hosts)->build();

//$bulk=['index'=>'search_engine_ani','type'=>'all_web_site_record','id'=>'dilidili'];
//$bulk['body']=['web_url'=>'http://www.dilidili.wang/','web_title'=>'D站'];
$params=[
    "index"=>'search_engine_ani',
    "type"=>"site_record",
    "body"=>[
        "site_record"=>[
            "_parent"=>[
                "type"=>"all_web_site_record"
            ],
            "properties"=>array(
                "web_url"=>array("type"=>"text","index"=>"not_analyzed"),//用于统计
                "title"=>array("type"=>"keyword","index"=>"analyzed"),//分词检索
                "hot"=>array("type"=>"long","index"=>"not_analyzed"),//用于统计
                "tag"=>array("type"=>"keyword","index"=>"not_analyzed"),//用于统计
            )
        ]
        ]
];
try{
    $res=$client->indices()->putMapping($params);
    print_r($res);
}catch (Exception $e){
    var_dump("Error".$e->getMessage());
}
