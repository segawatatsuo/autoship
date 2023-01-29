<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRemiseRequest;
use App\Http\Requests\UpdateRemiseRequest;
use App\Models\Remise;

class RemiseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreRemiseRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRemiseRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Remise  $remise
     * @return \Illuminate\Http\Response
     */
    public function show(Remise $remise)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Remise  $remise
     * @return \Illuminate\Http\Response
     */
    public function edit(Remise $remise)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateRemiseRequest  $request
     * @param  \App\Models\Remise  $remise
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRemiseRequest $request, Remise $remise)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Remise  $remise
     * @return \Illuminate\Http\Response
     */
    public function destroy(Remise $remise)
    {
        //
    }
}
