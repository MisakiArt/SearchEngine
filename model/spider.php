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
               $redis->hSetNx('MiProjectUrlHash', json_encode($v), $v);
            $html = self::getHtmlByUrl($v, $referer);
            if ($html) {
                $redis->Lpush('MiProjectUrlList',$v);
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
                    echo 'url number:'.$redis->hLen('MiProjectUrlHash') . PHP_EOL;
                    $this->getUrl($b, $str, $referer);
                }
            }
          }
        }
   	  }

   	  public static function getInformationByUrl($url,$referer,&$body){
          $html=self::getHtmlByUrl($url,$referer);
   	  	if($html){
   	  		phpQuery::newDocumentHtml($html);
   	  		$item1=pq('dd > h1');
   	  		$title=$item1->html();
   	  		$item2=pq('.play_cs');
   	  		$match="/<script.*<\/script>/U";
   	  		$a=preg_match($match,$item2->html(),$array,0);
            $body[]=['index'=>['_id'=>$url,'routing'=>'Dilidili']];
            $body[]=['title'=>$title,'script'=>$array[0],'url'=>$url];
   	  		// $match='/\d+/';
   	  		// $script=file_get_contents('http://www.dilidili.wang/plus/countlist.php?view=yes&aid=3103&mid=');
        //     $hot=preg_match($match,htmlentities($script),$matchArray);
        //     print_r($matchArray);
   	  	}

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
          curl_setopt($curl_handle, CURLOPT_FOLLOWLOCATION, 1);
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
           curl_setopt($curl_handle, CURLOPT_FOLLOWLOCATION, 1);
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