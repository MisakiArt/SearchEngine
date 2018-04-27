<?php
require_once dirname(__FILE__)."/model/phpQuery.php";
require_once dirname(__FILE__)."/model/spider.php";
require_once dirname(__FILE__).'/vendor/autoload.php';
require_once dirname(__FILE__)."/config.php";
ignore_user_abort();
ini_set('memory_limit','500M');
set_time_limit(0);



$redis = new redis();
$redis ->connect('127.0.0.1','6379');
$redis -> flushAll();
$config = new config();
$siteConfig = $config->getTargetSiteConfig();
$spider=new spider();
$referer=$siteConfig['target_site_referer'];
$spider->getUrl([$siteConfig['target_site_port']],$siteConfig['target_site_web_rule'],$referer);
$HashList = $redis->hGetAll('MiProjectUrlHash');
$date = date('Ymd');
$myfile = fopen(dirname(__FILE__)."/historyHashList/hashListLog".$date.".txt", "w") or die("Unable to open file!");
$txt = serialize($HashList);
fwrite($myfile, $txt);
fclose($myfile);
//$url=$redis -> hVals('MiProjectUrlHash');
//print_r($url);
//if(!$redis->hExists('MiProjectUrlHash', json_encode('http://www.dilidili.wang/anime/2018/'))){
//    echo 1;
//}
//$match='/http:\/\/www.dilidili.wang\/anime/';
//foreach ($url as $u){
//    if(!preg_match($match,$u))
//        $u='http://www.dilidili.wang'.$u;
//    $message=spider::getTitleByUrl($url,'',$referer);
//
//}
// 
//Connection was reset 目标网站服务器不稳定。
//网页为空，ajax方式请求网站，去network做同样的请求





















