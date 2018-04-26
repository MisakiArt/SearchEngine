<?php 
header('Access-Control-Allow-Origin:*');
require_once "model/phpQuery.php";
require_once "model/spider.php";
require 'vendor/autoload.php';

$array=[];
//if($_POST){
    $search='少女';
    if(preg_match('/[\x80-\xff_\w]+/u',$search))
    {
        $hosts = array('127.0.0.1:9200');
        $client = Elasticsearch\ClientBuilder::create()->setHosts($hosts)->build();
        $params=[
            'index'=>'search_engine_ani',
            'type'=>'search_record',
            'body'=>[
                "size"=>10,
                'query'=>[
                    'function_score'=>[
                        'query'=>[
                            'match'=>[
                                'title'=>[
                                    'query'=>$search,
                                    'fuzziness'=>'AUTO',
                                    "operator"=>'and'
                                ]
                            ]
                        ],
//                        'field_value_factor'=>[
//                            'field'=>'hot',
//                            'modifier'=>'log1p',
//                            'factor'=>0.1
//                        ]
                    ]
                ]
            ]
        ];
        $res=$client->search($params);
        if($res&&!empty($res['hits']['hits'])) {
            $res=$res['hits']['hits'];
            $array=['status'=>'success'];
            foreach ($res as $v){
                if(isset($v['_source']['script'])){
                if($v['_source']['script']==null){
                    $v['_source']['script']=0;
                }else{
                    $x='http://asdqwds.com';
                    $pattern='/^http.*"$/';
                    preg_match($pattern,$v['_source']['script'],$match);

                    if($match){
                        echo 111;
                        $hot=file_get_contents($match[0]);
                    }
                }

                }
	        $array['data'][]=['title'=>$v['_source']['title'],'source'=>$v['_routing'],'url'=>$v['_source']['url'],'hot'=>$v['_source']['script']];
            }
        }else{
            $array=['status'=>'error','errorType'=>'0'];
        }


    }else{
        $array=['status'=>'error','errorType'=>'1'];
    }
	echo json_encode($array);
//}