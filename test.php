<?php
   /**
   * 
   */
   class spider 
   {
   	  public $res=[];
   	  public $url=[];
   	  public $flag=0;
   	  public $context= ['http'=>['timeout'=>60]];


   	  public function getUrl($arr,$str){
   	  	$this->res=array_flip($this->res);
	    $this->res=array_keys($this->res);
	    $end_flag=count($this->res);
   	  	$b=[];
	    $a=[];
	foreach ($arr as $key => $v) {
	$curl_handle=curl_init();
     curl_setopt($curl_handle, CURLOPT_URL,$v);
     curl_setopt ( $curl_handle ,  CURLOPT_HEADER ,  false );
     curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 1);
     curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
     curl_setopt($curl_handle, CURLOPT_TIMEOUT, 20);
     curl_setopt($curl_handle, CURLOPT_SSL_VERIFYPEER, false);
     curl_setopt($curl_handle, CURLOPT_SSL_VERIFYHOST, false);
     if(($html = curl_exec($curl_handle)) ===  false )
      {
    echo  'Curl error: '  .  curl_error ( $curl_handle );
    // print_r(curl_getinfo($curl_handle));
       }
     curl_close($curl_handle);
		if($html){
			phpQuery::newDocumentHtml($html);
		$items=pq('a');
		foreach ($items as $item) {
			$a[]=pq($item)->attr('href');
		}
		foreach ($a as $k=>$v) {
	      if(!(strpos($v,$str)===false))
		$b[]=$v;
           }
        if(count($b)>10000) break;
		}
		else continue;
	  }
	  $b=array_flip($b);
	  $b=array_keys($b);
	  $this->res=array_merge($this->res,$b);
	  if($end_flag<count($this->res)||count($this->res)<10000){
	  	$this->flag++;
	  	if($this->flag>10){
	  		$this->res=array_flip($this->res);
	        $this->res=array_keys($this->res);
	  		print_r($this->res);
	  		exit;
	  	}
	  	$this->getUrl($b,$str);
	  }
   	  }

   	  public function getTitleByUrl($url,$titleStr){
   	  	$html=file_get_contents($url,false,stream_context_create($this->context));
   	  	if($html){
   	  		phpQuery::newDocumentHtml($html);
   	  		$items=pq('#lg > img');
   	  		print_r($items->attr('src'));
   	  	}

   	  }
   
   }


?>