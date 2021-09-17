<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Store;
use App\Services\CsvParseServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class ProductController extends Controller
{
    private CsvParseServiceInterface $csvParseService;

    /**
     * @param CsvParseServiceInterface $csvParseService
     */
    public function __construct(CsvParseServiceInterface $csvParseService)
    {
        $this->csvParseService = $csvParseService;
    }


    public function uploadCsv(Request $request){
        Log::debug("Request to upload CSV.", [$this, $request]);
        $file = $request->file('file');
        $storeIds = $request->input('selectedStores');
        Log::debug("Store Ids:",[$this, $storeIds]);
        $stores = Store::findMany($storeIds);
        Log::debug("Found the following stores.",[$this, $stores]);
        $this->csvParseService->parseCsv($stores, $file);


        $stores = Store::all();
        return Inertia::render('Product/Index', ['stores' => $stores]);
    }



    public function index() {
        $stores = Store::all();
        return Inertia::render('Product/Index', ['stores' => $stores]);
    }
}
