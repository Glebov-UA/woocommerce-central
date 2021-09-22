<?php /** @noinspection PhpMissingFieldTypeInspection */

namespace App\Providers;

use App\Services\CsvParseService;
use App\Services\CsvParseServiceInterface;
use App\Services\CsvParseServiceV2;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{

    public $singletons = [
        CsvParseServiceInterface::class => CsvParseServiceV2::class,
    ];

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
