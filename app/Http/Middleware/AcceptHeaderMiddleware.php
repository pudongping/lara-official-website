<?php
/**
 *  设置 http 请求头信息
 *
 * Created by PhpStorm.
 * User: Alex
 * Date: 2020/2/3
 * Time: 23:53
 */

namespace App\Http\Middleware;

use Closure;

class AcceptHeaderMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $request->headers->set('Accept', 'application/json; charset=utf-8');

        return $next($request);
    }
}
