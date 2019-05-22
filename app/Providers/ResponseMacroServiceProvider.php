<?php

namespace App\Providers;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\ServiceProvider;

class ResponseMacroServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Response::macro('success', function ($data = [], $status = 200) {
            if ($data instanceof Collection) {
                $data = ['data' => $data->toArray()];
            }

            if ($data instanceof LengthAwarePaginator || $data instanceof Paginator) {
                $data = $data->toArray();
                if (app()->environment(['local', 'dev'])) {
                    $data['queries'] = \DB::getQueryLog();
                }
            }

            if (is_string($data)) {
                $data = ['message' => $data];
            }

            return Response::json([
                    'success' => true,
                ] + $data, (int) $status ? $status : 200);
        });

        Response::macro('error', function ($message = null, $status = 500) {
            return Response::json([
                'success' => false,
                'message' => $message,
            ], (int) $status ? $status : 500);
        });
    }
}
