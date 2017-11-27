<?php
   /**
   * 
   */
   class spider 
   {
   	  public $res=[];
   	  public $url=[];

   	  public function getUrl($arr){
   	  	$b=[];
	$a=[];
	foreach ($arr as $key => $v) {
		$html=file_get_contents($v);
		phpQuery::newDocumentHtml($html);
		$items=pq('a');
		foreach ($items as $item) {
			$a[]=pq($item)->attr('href');
		}
		$match='/^^((https|http|ftp|rtsp|mms)?:\/\/)[^\s]+$/';
		foreach ($a as $k=>$v) {
	      if(preg_match($match, $v))
		$b[]=$v;
           }
	  }
	  $b=array_flip($b);
	  $b=array_flip($b);
	  $this->res=array_merge($this->res,$b);
	  if(count($this->res)<10000){
	  	$this->getUrl($b);
	  }
   	  }
   
   }


?>