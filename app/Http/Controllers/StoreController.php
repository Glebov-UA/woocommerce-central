<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class StoreController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Store::all();
        return Inertia::render('Store/Index', ['data' => $data]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = User::find(Auth::id());
        $store = new Store();
        $store->url = $request->input('url');
        $store->consumer_key = $request->input('consumer_key');
        $store->consumer_secret = $request->input('consumer_secret');
        $user->stores()->save($store);

        $data = Store::all();
        return Inertia::render('Store/Index', ['data' => $data]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Store  $store
     * @return \Illuminate\Http\Response
     */
    public function show(Store $store)
    {
        $data = Store::all();
        return Inertia::render('Index', ['data' => $data]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Store  $store
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Store $store)
    {
        $input = $request->all();

        $store->fill($input)->save();

        $data = Store::all();
        return Inertia::render('Store/Index', ['data' => $data]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Store  $store
     * @return \Illuminate\Http\Response
     */
    public function destroy(Store $store)
    {
        $store->delete();
        $data = Store::all();
        return Inertia::render('Store/Index', ['data' => $data]);
    }
}
