<?php
/**
 * Created by PhpStorm.
 * User: PC
 * Date: 2018/4/27
 * Time: 8:44
 */

class config
{
    const CODERNAME = 'Misaki';
    public function getRedisConfig($key){
        if($key == md5($this::CODERNAME.date('Y-m-d'))){
            return [
                'ip'=>'127.0.0.1',
                'port'=>'6379',
            ];
        }else{
            return [];
        }

    }

    public function getElasticSearchConfig($key){
        if($key == md5($this::CODERNAME.date('Y-m-d'))){
            return [
                'ip'=>'127.0.0.1',
                'port'=>'9200',
            ];
        }else{
            return [];
        }

    }

    public function getTargetSiteConfig(){
        return [
            'target_site_port'=>'http://www.dilidili.wang',
            'target_site_web_rule'=>'/anime/',
            'target_site_referer'=>'http://www.dilidili.wang',
            'target_site_update_port'=>'http://www.dilidili.wang'

        ];

    }
    public function getReloadDate(){
        return '2018-04-27';

    }


}