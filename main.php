<?php
require_once "model/phpQuery.php";
require_once "model/test.php";
require 'vendor/autoload.php';
ignore_user_abort();
set_time_limit(0);


$spider=new spider();
$referer='http://www.dilidili.wang';
$spider->getUrl(['http://www.dilidili.wang/'],'/anime/',$referer);
$url=$spider->res;
$match='/http:\/\/www.dilidili.wang\//';
foreach ($url as $u){
    if(!preg_match($match,$u))
        $u='http://www.dilidili.wang'.$u;
    $message=spider::getTitleByUrl($url,'',$referer);

}
// 
//Connection was reset 目标网站服务器不稳定。
//网页为空，ajax方式请求网站，去network做同样的请求





















