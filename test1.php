<?php
$url=['www.baidu.com/dasd/qweq/sda','www.xinlang.com/asd/qwe/asd','www.ok.com/sad','www.baidu.com/asdqwe/xxxx/qweq'];
$new_arr=[];
foreach ($url as $v) {
	if(!(strpos($v,'www.baidu.com')===false)){	
		$new_arr[]=$v;
	}
}
print_r($new_arr);
?>