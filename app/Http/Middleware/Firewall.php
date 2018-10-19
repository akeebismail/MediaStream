<?php

namespace App\Http\Middleware;

use App\FireWallBannedIp;
use Carbon\Carbon;
use Closure;
use Illuminate\Support\Facades\Cache;

class Firewall
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
        if ($this->isIpAddressBanned()){
            return res(423, 'Sadly your IP address has been banned from access Kibb.  ');
        }
        return $next($request);
    }

    private function isIpAddressBanned()
    {
        $banned_ip_address = Cache::remember('firewall-banned-ips',60 * 24 * 7, function (){
           return FireWallBannedIp::query()->where('unban_at','>=',Carbon::now())->pluck('ip_address');
        });

        return $banned_ip_address->contains(getRequestIpAddress());
    }
}
