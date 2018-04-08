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


   	  public function getUrl($arr,$str,$referer=''){
          $redis = new redis();
          $redis ->connect('127.0.0.1','6379');
	    $end_flag=$redis -> hLen('MiProjectUrlHash');
   	  	 $b=[];
	     $a=[];
	     foreach ($arr as $key => $v) {
           if (!$redis->hExists('MiProjectUrlHash', json_encode($v))) {
            $html = self::getHtmlByUrl($v, $referer);
            if ($html) {
                $redis->hSetNx('MiProjectUrlHash', json_encode($v), $v);
                $redis->Lpush('MiProjectUrlList',$v);
                echo $v . PHP_EOL;
                phpQuery::newDocumentHtml($html);
                $items = pq('a');
                foreach ($items as $item) {
                    $a[] = pq($item)->attr('href');
                }
                foreach ($a as $k => $v) {
                    if (!(strpos($v, $str) === false))
                        $b[] = $v;
                }
                if (count($b) > 10000) break;//单个网页上限
            }
            if($b) {
                $b = array_flip($b);
                $b = array_keys($b);
                if ($end_flag < $redis->hLen('MiProjectUrlHash') || $redis->hLen('MiProjectUrlHash') < 10000) {
                    $this->flag++;
                    echo $redis->hLen('MiProjectUrlHash') . PHP_EOL;
//	  	if($this->flag>5){
//	  		$this->res=array_flip($this->res);
//	        $this->res=array_keys($this->res);
//	  		exit;
//	  	}
                    $this->getUrl($b, $str, $referer);
                }
            }
          }
        }
   	  }

   	  public static function getTitleByUrl($url,$titleStr,$referer){
          $html=self::getHtmlByUrl($url,$referer);
   	  	if($html){
   	  		phpQuery::newDocumentHtml($html);
   	  		$item1=pq('dd > h1');
   	  		$title=$item1->html();
   	  		$item2=pq('.play_cs');
   	  		$match="/<script.*<\/script>/U";
   	  		$a=preg_match($match,$item2->html(),$array);
   	  		$params=['title'=>$title,'hot'=>$array[0],'url'=>$url];
   	  		return $params;
   	  	}
   	  	return [];

   	  }


      public static function getHtmlByUrl(&$url,$referer=''){
        $curl_handle=curl_init();
          curl_setopt($curl_handle, CURLOPT_URL,$url);
          curl_setopt($curl_handle, CURLOPT_HEADER, false );
          curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 1);
          curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
          curl_setopt($curl_handle, CURLOPT_TIMEOUT, 20);
          curl_setopt($curl_handle, CURLOPT_SSL_VERIFYPEER, false);
          curl_setopt($curl_handle, CURLOPT_SSL_VERIFYHOST, false);
          if(!empty($referer)){
              curl_setopt($curl_handle,CURLOPT_REFERER,$referer);
          }

          if(($html = curl_exec($curl_handle)) ===  false )
          {
              $url=$referer.$url;
              $html = self::getHtmlByUrl2($url,$referer);
//              echo  'Curl error: '  .  curl_error ( $curl_handle );
          }
          curl_close($curl_handle);
          return $html;
      }
       public static function getHtmlByUrl2(&$url,$referer=''){
           $curl_handle=curl_init();
           curl_setopt($curl_handle, CURLOPT_URL,$url);
           curl_setopt($curl_handle, CURLOPT_HEADER, false );
           curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 1);
           curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
           curl_setopt($curl_handle, CURLOPT_TIMEOUT, 20);
           curl_setopt($curl_handle, CURLOPT_SSL_VERIFYPEER, false);
           curl_setopt($curl_handle, CURLOPT_SSL_VERIFYHOST, false);
           if(!empty($referer)){
               curl_setopt($curl_handle,CURLOPT_REFERER,$referer);
           }

           if(($html = curl_exec($curl_handle)) ===  false )
           {
//              echo  'Curl error: '  .  curl_error ( $curl_handle );
              $html='';
           }
           curl_close($curl_handle);
           return $html;
       }

   
   }


?>