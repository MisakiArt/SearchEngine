<?php
require_once "phpQuery.php";
require_once "test.php";

$spider=new spider();
$spider->getUrl(['http://www.baidu.com']);
print_r($spider->res);























?>