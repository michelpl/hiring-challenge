<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreChargeRequest;
use App\Http\Requests\UpdateChargeRequest;
use App\Models\Charge;
use App\Services\ChargeService;
use Illuminate\Http\Response;

class ChargeController extends Controller
{
    private ChargeService $chargeService;
    function __construct(ChargeService $chargeService)
    {
        $this->chargeService = $chargeService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        (int) $rowsPerPage = 10;
        return $this->chargeService->paginatedList($rowsPerPage);
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
     * @param  \App\Http\Requests\StoreChargeRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreChargeRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Charge  $charge
     * @return \Illuminate\Http\Response
     */
    public function show(Charge $charge)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Charge  $charge
     * @return \Illuminate\Http\Response
     */
    public function edit(Charge $charge)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateChargeRequest  $request
     * @param  \App\Models\Charge  $charge
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateChargeRequest $request, Charge $charge)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Charge  $charge
     * @return \Illuminate\Http\Response
     */
    public function destroy(Charge $charge)
    {
        //
    }
}
