<?php
namespace app\portal\controller;
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2017 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 老猫 <thinkcmf@126.com>
// +----------------------------------------------------------------------
use cmf\controller\HomeBaseController;

class IndexController extends HomeBaseController
{
	
	public static function getconfig()
	{
		// Cache::flush();
		$nonceStr = str_random(32);
		$timeStamp = time();
		// $url = urldecode(request()->fullurl());
		$url = urldecode(request()->url());
		 $corpAccessToken = self::getAccessTokEN();
		 $ticket = self::getTicket($corpAccesStOKEN);
		 $signature = self::sign($ticket, $nonceStr, $timeStamp, $url);
															   
	   $config = array(
		   'url' => $url,
		   'nonceStr' => $nonceStr,
		   'timeStamp' => $timeStamp,
		   'corpId' => config('custom.dingtalk.corpid'),
		   'signature' => $signature,
		   'ticket' => $ticket,
		   'agentId' => config('custom.dingtalk.agentidlist.' . self::$APPNAME),       // such as: config('custom.dingtalk.agentidlist.approval')      // request('app')
		   'appname' => self::$APPNAME,
		);
								   
		 return $config;
		 // RETURN JSON_encode($config, JSON_UNESCAPED_SLASHES);
		 // return response()->json($config);
	}
	
	/*
	public static function getAccessToken() {
        $accessToken = Cache::remember('access_token', 7200/60 - 5, function() {        // 减少5分钟来确保不会因为与钉钉存在时间差而导致的问题
            $url = 'https://oapi.dingtalk.com/gettoken';
            $corpid = config('custom.dingtalk.corpid');
            $corpsecret = config('custom.dingtalk.corpsecret');
            $params = compact('corpid', 'corpsecret');
            // $reply = $this->get($url, $params);
            $reply = self::get($url, $params);
            $accessToken = $reply->access_token;
            return $accessToken;
        });
        return $accessToken;
    }  */
	
	public static function getAccessToken() {
	//$accessToken = Cache::remember('access_token', 7200/60 - 5, function() {        // 减少5分钟来确保不会因为与钉钉存在时间差而导致的问题
		$url = 'https://oapi.dingtalk.com/gettoken';
		$corpid = config('custom.dingtalk.corpid');
		$corpsecret = config('custom.dingtalk.corpsecret');
		$params = compact('corpid', 'corpsecret');
		// $reply = $this->get($url, $params);
		$reply = self::get($url, $params);
		$accessToken = $reply->access_token;
		return $accessToken;
	//});
	//return $accessToken;
    }
	
	/*  public static function getTicket($access_token)
    {
        $ticket = Cache::remember('ticket', 7200/60 - 5, function() use($access_token) {
            $url = 'https://oapi.dingtalk.com/get_jsapi_ticket';
            $params = compact('access_token');
            $reply = self::get($url, $params);
            $ticket = $reply->ticket;
            return $ticket;
        });
        return $ticket;
    } */
	
	  public static function getTicket($access_token)
    {
       // $ticket = Cache::remember('ticket', 7200/60 - 5, function() use($access_token) {
            $url = 'https://oapi.dingtalk.com/get_jsapi_ticket';
            $params = compact('access_token');
            $reply = self::get($url, $params);
            $ticket = $reply->ticket;
            return $ticket;
       // });
      //  return $ticket;
    }
	
	  private static function get($url, $params)
    {
        $response = \Httpful\Request::get($url . '?' . http_build_query($params))->send();
        if ($response->hasErrors()) {
            throw new \Exception($response->hasErrors());
        }
        if (!$response->hasBody()) {
            throw new \Exception("No response body.");
        }
        if ($response->body->errcode != 0) {
            throw new \Exception($response->body->errmsg);
        }
        return $response->body;
    }

    public static function sign($ticket, $nonceStr, $timeStamp, $url)
    {
        $rawstring = 'jsapi_ticket=' . $ticket .
                     '&noncestr=' . $nonceStr .
                     '&timestamp=' . $timeStamp .
                     '&url=' . $url;
        $signature = sha1($rawstring);    
        
        return $signature;
    }
	
    public function index()
    {
		session_start();
		//$_SESSION['token']='123';
		
        return $this->fetch(':index');
    }
}
