<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreChargeRequest;
use App\Http\Requests\UpdateChargeRequest;
use App\Models\Charge;
use App\Services\ChargeService;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use PhpParser\Node\Stmt\TryCatch;

class ChargeController extends Controller
{
    private ChargeService $chargeService;
    private array $validationRules = [
            'name' => 'required|max:255|string',
            'governmentId' => 'required|min:11|max:13|string',
            'email' => 'required|email',
            'debtAmount' => 'required|decimal:2|min:0.01|max:11',
            'debtDueDate' => 'required|date',
            'debtId' => 'required|integer|min:1|max:99999999'
    ];

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
        return $this->chargeService->paginatedChargeList($rowsPerPage);
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $request->validate($this->validationRules);


            //return response($this->message, 201);

        } catch (\Exception $exception) {
            return $this->returnResponseError($exception);
        }

        return $request;
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

    private function returnResponseError(\Exception $exception): Response
    {
        $message = $exception->getMessage();
        $code = $exception->getCode();

        if (isset($exception->validator)) {
            $validator = $exception->validator;
            $message = [
                'message' => $exception->getMessage(),
                $validator->messages()
            ];

            $code = 400;
        }

        return response($message, $code);
    }
        
}
