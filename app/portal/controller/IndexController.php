<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2017 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 老猫 <thinkcmf@126.com>
// +----------------------------------------------------------------------
namespace app\portal\controller;

use cmf\controller\HomeBaseController;

class IndexController extends HomeBaseController
{
	public static function getconfig()
	{
		// Cache::flush();
	
		
		 $str="0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
		$key = "";
		for($i=0;$i<32;$i++)
		{
			 $key .= $str{mt_rand(0,32)};   
		}
		$nonceStr = $key;
		//var_dump($nonceStr);
		$timeStamp = time();
		// $url = urldecode(request()->url().'index.php');
		$urls= $_SERVER["REQUEST_SCHEME"].'://'.$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];

		//$url = urldecode(request()->url());
		$url=urldecode($urls);
		 $corpAccessToken = self::getAccessToken();
		// var_dump($corpAccessToken);
		
		 $ticket = self::getTicket($corpAccessToken);
		// var_dump($ticket);
		 $signature = self::sign($ticket, $nonceStr, $timeStamp, $url);
	
					
	   $config = array(
		   'url' => $url,
		   'nonceStr' => $nonceStr,
		   'timeStamp' => $timeStamp,
		   //'corpId' => config('custom.dingtalk.corpid'),
		   'corpId' => 'dingb06b545a3dde55a535c2f4657eb6378f',
		   'signature' => $signature,
		   'ticket' => $ticket,
		   //'agentId' => config('custom.dingtalk.agentidlist.' . self::$APPNAME),       // such as: config('custom.dingtalk.agentidlist.approval')      // request('app')
		  // 'agentId'=>'115514943',
			'agentId'=>'115898362',
		   //'appname' => self::$APPNAME,
		);
		var_dump($config['agentId']);		   
		 return $config;
		 // RETURN JSON_encode($config, JSON_UNESCAPED_SLASHES);
		 // return response()->json($config);
	}
	
	public static function getAccessToken() {
	//$accessToken = Cache::remember('access_token', 7200/60 - 5, function() {        // 减少5分钟来确保不会因为与钉钉存在时间差而导致的问题
		if(empty($_SESSION['token'])){
			$url = 'https://oapi.dingtalk.com/gettoken';
			//$corpid = config('custom.dingtalk.corpid');
			//$corpsecret = config('custom.dingtalk.corpsecret');
			$corpid='dingb06b545a3dde55a535c2f4657eb6378f';
			$corpsecret='qrJSw4aGRMGMq-xc0B1wpXSK-UtC1ReO7bd0JyYRHht9lxZ8XtI0TnTJ-79kucJJ';
						 
			$params = compact('corpid', 'corpsecret');
			// $reply = $this->get($url, $params);
			$reply = self::get($url, $params);
			$accessToken = $reply->access_token;
			$_SESSION['token']=$accessToken;
		}else{
			$accessToken=$_SESSION['token'];
		}
		
		return $accessToken;
	//});
	//return $accessToken;
    }
	
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
	
	//获取用户信息：
	  public function getuserinfo($code)
    {
//        $corpid = 'ding6ed55e00b5328f39';
//        $corpsecret = 'gdQvzBl7IW5f3YUSMIkfEIsivOVn8lcXUL_i1BIJvbP4kPJh8SU8B8JuNe8U9JIo';
        // $url = 'https://oapi.dingtalk.com/gettoken';
        // $params = compact('corpid', 'corpsecret');
        // $reply = $this->get($url, $params);
        // $access_token = $reply->access_token;
        $access_token = self::getAccessToken();
        // Get user info
        $url = 'https://oapi.dingtalk.com/user/getuserinfo';
        $params = compact('access_token', 'code');
        $userInfo = $this->get($url, $params);//获取登录这信息
        // get erp user info and set session userid
		//查询自己的数据库：
       /* $user_erp = DB::table('users')->where('dtuserid', $userInfo->userid)->first();
        $userid_erp = -1;
        if (!is_null($user_erp))
        {
            $userid_erp = $user_erp->id;
            session()->put('userid', $userid_erp);
            // login 
            if (!Auth::check())
            {
                Auth::loginUsingId($userid_erp);
            }
        }
        $user = [
            'deviceId' => $userInfo->deviceId,
            'errcode' => $userInfo->errcode,
            'errmsg' => $userInfo->errmsg,
            'is_sys' => $userInfo->is_sys,
            'sys_level' => $userInfo->sys_level,
            'userid' => $userInfo->userid,
            'userid_erp' => $userid_erp,
        ];
		*/
        return response()->json($user);
    }
	
    public function index()
    {
		//$config=$this->getconfig();
		//$user_id=$_GET['user_id'];
		//$level=$_GET['level'];
		//var_dump($user_id);
		//$this->assign('user_id', $user_id);
        return $this->fetch(':index');
    }
	
	public function search(){
		return $this->fetch('search');
	}
}
