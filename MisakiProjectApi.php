<?php 
header('Access-Control-Allow-Origin:*');
require_once "model/phpQuery.php";
require_once "model/spider.php";
require_once 'vendor/autoload.php';
require_once dirname(__FILE__)."/config.php";

$array=[];
//if($_POST&&!empty($_POST['type'])){
    $search = !empty($_POST['search'])?$_POST['search']:'';
    $type = !empty($_POST['type'])?$_POST['type']:1;
    $spider = new spider();
    $spider->setModifyDate();
    $config = new config();
    $elastic_search = $config ->getElasticSearchConfig(md5($config::CODERNAME.$spider->modify_date));
    $hosts = [$elastic_search['ip'].':'.$elastic_search['port']];
    $client = Elasticsearch\ClientBuilder::create()->setHosts($hosts)->build();
    if($type ==2){
        if(preg_match('/[\x80-\xff_\w]+/u',$search))
        {
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
                            'field_value_factor'=>[
                                'field'=>'hot',
                                'modifier'=>'log1p',
                                'factor'=>0.1,
                                "missing"=>1
                            ],
                            "boost_mode"=>"sum",
                            "max_boost"=>1.5
                        ]
                    ]
                ]
            ];
            try{
                $res=$client->search($params);
            }catch (Exception $e){
                echo json_encode(['status'=>'error','errorType'=>3]);exit;

            }
            if($res&&!empty($res['hits']['hits'])) {
                $res=$res['hits']['hits'];
                $array=['status'=>'success'];
                foreach ($res as $v){
                    if(isset($v['_source']['has_special_param'])){
                        if($v['_source']['has_special_param']==1){
                            if(!empty($v['_source']['script'])){
                                $pattern='/^http.*"$/';
                                preg_match($pattern,$v['_source']['script'],$match);
                                if(!empty($match)){
                                    $hot=file_get_contents($match[0]);
                                    $array['data'][]=['title'=>$v['_source']['title'],'source'=>$v['_routing'],'url'=>$v['_source']['url'],'hot'=>$hot];
                                }

                            }
                        }else{
                            $array['data'][]=['title'=>$v['_source']['title'],'source'=>$v['_routing'],'url'=>$v['_source']['url'],'hot'=>$v['_source']['url']['hot']];
                        }
                    }else{
                        $array['data'][]=['title'=>$v['_source']['title'],'source'=>$v['_routing'],'url'=>$v['_source']['url'],'hot'=>$v['_source']['url']['hot']];
                    }
                }
                if(count($array['data'])>10){
                    $array['data'] = array_slice($array['data'],0,10);
                }
            }else{
                $array=['status'=>'error','errorType'=>'0'];
            }


        }else{
            $array=['status'=>'error','errorType'=>'1'];
        }

    }else {
        $params = [
            'index' => 'search_engine_ani',
            'type' => 'search_record',
            'body' => [
                "size" => 10,
                'query' => [
                    'function_score' => [
                        'field_value_factor' => [
                            'field' => 'hot',
                            'modifier' => 'log1p',
                            'factor' => 0.1,
                            "missing" => 1
                        ],
                        "boost_mode" => "sum",
                        "max_boost" => 1.5
                    ]
                ]
            ]
        ];
        try {
            $res = $client->search($params);
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'errorType' => 3]);
            exit;

        }

        if ($res && !empty($res['hits']['hits'])) {
            $res = $res['hits']['hits'];
            $array = ['status' => 'success'];
            foreach ($res as $v) {
                if (isset($v['_source']['has_special_param'])) {
                    if ($v['_source']['has_special_param'] == 1) {
                        if (!empty($v['_source']['script'])) {
                            $array['data'][] = ['title' => $v['_source']['title'], 'source' => $v['_routing'], 'url' => $v['_source']['url'], 'hot' => $v['_source']['script']];
                        } else {
                            $array['data'][] = ['title' => $v['_source']['title'], 'source' => $v['_routing'], 'url' => $v['_source']['url'], 'hot' => 'unKnow'];
                        }

                    } else {
                        $array['data'][] = ['title' => $v['_source']['title'], 'source' => $v['_routing'], 'url' => $v['_source']['url'], 'hot' => $v['_source']['url']['hot']];
                    }
                } else {
                    $array['data'][] = ['title' => $v['_source']['title'], 'source' => $v['_routing'], 'url' => $v['_source']['url'], 'hot' => $v['_source']['url']['hot']];
                }
            }
            if (count($array['data']) > 10) {
                $array['data'] = array_slice($array['data'], 0, 10);
            }
        } else {
            $array = ['status' => 'error', 'errorType' => '0'];
        }
    }
	echo json_encode($array);
//}