<?php
require_once dirname(__FILE__)."/../config.php";

   class spider 
   {
      public $modify_date;
   	  public $res=[];
   	  public $url=[];
   	  public $urlCount = 0;
   	  public $hashCount = 0;


   	  public function getUrl($arr,$str,$referer=''){
   	      $this->setModifyDate();
   	      $redis_config = new config();
   	      $redis_config = $redis_config->getRedisConfig(md5($redis_config::CODERNAME.$this->modify_date));

          $redis = new redis();
          $redis ->connect($redis_config['ip'],$redis_config['port']);
	      $end_flag=$this->urlCount;
	      unset($temp_array);
          $temp_array=[];
	      $url=[];
	     foreach ($arr as $key => $v) {
           if (!$redis->hExists('MiProjectUrlHash', json_encode($v))) {
               $redis->hSetNx('MiProjectUrlHash', json_encode($v), $v);
               $this->hashCount++;
            $html = self::getHtmlByUrl($v, $referer);
            if ($html) {
                $this->urlCount++;
                $redis->Lpush('MiProjectUrlList',$v);
                phpQuery::newDocumentHtml($html);
                unset($html);
                $items = pq('a');
                foreach ($items as $item) {
                    $url[] = pq($item)->attr('href');
                }
                unset($item);
                foreach ($url as $k => $v) {
                    if (!(strpos($v, $str) === false))
                        $temp_array[] = $v;
                }
                unset($url);
                $temp_array = array_flip($temp_array);
                $temp_array = array_keys($temp_array);
                if (count($temp_array) > 1000)
                    $temp_array = array_slice($temp_array,0,1000);//单个网页上限
            }
            if($temp_array) {
                if ($end_flag < $this->urlCount && $this->urlCount < 10000) {
                    echo 'url number:'.$this->urlCount . PHP_EOL;
                    $this->getUrl($temp_array, $str, $referer);
                }
            }
          }
        }
   	  }

   	  public static function getInformationByUrl($url,$referer,&$body){
          $html=self::getHtmlByUrl($url,$referer);
   	  	if($html){
   	  	    $date = date('Y-m-d');
   	  		phpQuery::newDocumentHtml($html);
   	  		$item1=pq('dd > h1');
   	  		$title=$item1->html();
   	  		$item2=pq('.play_cs');
   	  		$match="/<script.*<\/script>/U";
   	  		$a=preg_match($match,$item2->html(),$array,0);
            $body[]=['index'=>['_id'=>$url,'routing'=>'Dilidili']];
            $body[]=['title'=>$title,'script'=>isset($array[0])?$array[0]:'','url'=>$url,'create_time'=>strtotime($date),'update_time'=>strtotime($date),'has_special_param'=>true];
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

       public function setModifyDate(){
           $this->modify_date = date('Y-m-d');
       }

   
   }


?>