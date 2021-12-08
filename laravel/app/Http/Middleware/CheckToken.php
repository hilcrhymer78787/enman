<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\UserService;

class CheckToken
{
    /**
     * 送信されてきたリクエストの処理
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
      $loginInfo = (new UserService())->getLoginInfoByToken($request->header('token'));
      if($loginInfo){
        return redirect('/user/bearer_authentication');
      }
      return $next($request);
    }
}
