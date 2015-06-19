<?php  namespace Restboat\Providers;

use Illuminate\Support\ServiceProvider;
use Restboat\Models\Request;
use Restboat\Models\RequestLog;
use Restboat\Observers\RequestLogObserver;
use Restboat\Observers\RequestObserver;
use Restboat\Services\RequestLimiterService;

class ObserverServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap any necessary services.
     *
     * @return void
     */
    public function boot()
    {
        Request::observe( new RequestObserver(new RequestLimiterService) );
        RequestLog::observe( new RequestLogObserver(new RequestLimiterService) );
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
    }

}