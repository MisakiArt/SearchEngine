<?php
require_once "phpQuery.php";
require_once "test.php";
ignore_user_abort();
set_time_limit(0);


$spider=new spider();
$spider->getUrl(['http://www.dilidili.wang/'],'http://www.dilidili.wang/anime');
// $spider->getTitleByUrl('http://www.baidu.com');
// 
//Connection was reset 目标网站服务器不稳定。
//网页为空，ajax方式请求网站，去network做同样的请求





















