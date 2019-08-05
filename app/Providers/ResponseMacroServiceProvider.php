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

        Response::macro('report', function ($view, $data = [], $type = 'portrait', $pageType = 'a4') {
            if (app('request')->get('format') === 'view') {
                return reports_view_pdf($view, $data, true);
            }

            return reports_view_pdf($view, $data)
                ->setPaper($pageType, $type)
                ->stream();
        });

        Response::macro('reportLandscape', function ($view, $data = [], $pageType = 'a4', $filename = 'document.pdf') {
            if (app('request')->get('format') === 'view') {
                return reports_view_pdf($view, $data, true);
            }

            return reports_view_pdf($view, $data)
                ->setPaper($pageType, 'landscape')
                ->stream($filename);
        });

        Response::macro('reportAsView', function ($view, $data = []) {
            return reports_view_pdf($view, $data, true);
        });

        Response::macro('reportCustomDimensions', function ($view, $data = [], $dimensions = [0, 0, 500, 500]) {
            if (app('request')->get('format') === 'view') {
                return reports_view_pdf($view, $data, true);
            }

            return reports_view_pdf($view, $data)
                ->setPaper($dimensions)
                ->stream();
        });
    }
}
