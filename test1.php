<?php
require 'vendor/autoload.php';
require_once "model/phpQuery.php";
require_once "model/spider.php";
//$redis=new Redis();
// $redis->connect('127.0.0.1',6379);
$hosts = array('127.0.0.1:9200');
$client = Elasticsearch\ClientBuilder::create()->setHosts($hosts)->build();
$params = [
   'index'=>'search_engine_ani',
   'type'=>'site_record',
    'id'=>'Dilidili',
   'body'=>[
       'web_url'=>'http://www.dilidili.wang/',
       'web_title'=>'D站'
   ]
];
$resp = $client->index($params);
print_r($resp);
//var_dump($resp);
//    $b=[];
//    $a=[];
//    $url='http://www.baidu.com';
//            $html = spider::getHtmlByUrl($url,'');
//            if ($html) {
//                phpQuery::newDocumentHtml($html);
//                $items = pq('a');
//                foreach ($items as $item) {
//                    $a[] = pq($item)->attr('href');
//                }
//                foreach ($a as $k => $v) {
//                   echo $v.PHP_EOL;
//                }
//            }
//foreach ($urls as $k => $v) {
//    if (!(strpos($v, $str) === false))
//        $b[] = $v;
//}
//$bulk=['index'=>'search_engine_ani','type'=>'search_record'];
//while(true){
//                handle($params=$redis->brPop($key));
//                $bulk['body'][]=spider::getInformationByUrl($key,$referer);
//                if(count($bulk)>200){
//                    $client->bulk($bulk);
//                    $bulk=[];
//                    $bulk=['index'=>'search_engine_ani','type'=>'search_record'];
//                }
//}

//$text='http://www.dilidili.wang/anime/201607/';
//$match='/http:\/\/www.dilidili.wang\//';
//if(preg_match($match,$text)){
//    echo 1;
//}
// $referer='http://www.dilidili.wang';
// $url='http://www.dilidili.wang/anime/gangtieshangz/';
// $message=spider::getInformationByUrl($url,'',$referer);
// print_r($message);





?>