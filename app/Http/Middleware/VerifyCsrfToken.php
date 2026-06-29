<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        // JANGAN kosongkan untuk production!
        // Tapi untuk testing sementara, tambahkan:
        // 'login',
        // 'api/*',
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, \Closure $next)
    {
        // Tambahkan ini untuk ngrok
        if ($this->isNgrokRequest($request)) {
            return $next($request);
        }
        
        return parent::handle($request, $next);
    }

    /**
     * Check if request is from ngrok
     */
    protected function isNgrokRequest($request)
    {
        $host = $request->getHost();
        return str_contains($host, 'ngrok-free.dev') || str_contains($host, 'ngrok.io');
    }
}