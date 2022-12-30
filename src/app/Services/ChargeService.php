<?php

namespace App\Services;

use App\Factories\ChargeMailFactory;
use App\Models\Charge;
use App\Models\ChargeMail;
use App\Repositories\LogRepository;
use Illuminate\Support\Facades\DB;
use Exception;

class ChargeService 
{
    const STATUS_CREATED = 'created';
    const STATUS_SENT = 'sent';
    const CHARGES_TABLE = 'charges';
    private BoletoService $paymentService;
    private Charge $charge;
    private EmailService $emailService;
    private ChargeMail $chargeMail;
    private ChargeMailFactory $chargeMailFactory;

    public function __construct(
        BoletoService $boletoService, 
        Charge $charge, 
        EmailService $emailService,
        ChargeMail $chargeMail,
        ChargeMailFactory $chargeMailFactory
    ){
        $this->charge = $charge;
        $this->paymentService = $boletoService;
        $this->emailService = $emailService;
        $this->chargeMail = $chargeMail;
        $this->chargeMailFactory = $chargeMailFactory;
    }

    public function paginatedChargeList(Int $rowsPerPage = 1)
    {
        LogRepository::info('Returning charge list...');
        return Charge::simplePaginate($rowsPerPage);
    }

    public function createCharge(Charge $charge): Charge | null 
    {
        try {
            DB::beginTransaction();

            $createdCharge = $charge->firstOrCreate(
                ['debt_id' => $charge->debt_id],
                [
                    'name' => $charge->name,
                    'government_id' => $charge->government_id,
                    'email' => $charge->email,
                    'debt_amount' => $charge->debt_amount,
                    'debt_due_date' => $charge->debt_due_date,
                    'debt_id' => $charge->debt_id,
                    'paid_at' => $charge->paid_at,
                    'paid_amount' => $charge->paid_amount,
                    'paid_by' => $charge->paid_by
                ]
            );
            $this->paymentService->createChargePaymentMethod($createdCharge);

            DB::commit();

            LogRepository::info('Charge created for debt_id: ' . $createdCharge->debt_id);

            return $createdCharge;

        } catch (Exception $e) {
            DB::rollBack();

            LogRepository::warning(
                'Could not create charge: ' . 
                serialize($charge) . ' | ' . 
                $e->getMessage()
            );
            return null;
        }
    }

    public function sendChargeToCustomers()
    {
        try{
            LogRepository::info('Starting e-mail sending...');

            $chargeList =  
                Charge::with(
                    'boleto:charge_id,barcode,government_id,amount,debt_due_date'
                )
                ->where('status', self::STATUS_CREATED)
                ->get();

            $chargeMails = [];
            
            foreach($chargeList as $charge) {
                $chargeMails[] = $this->chargeMailFactory->createFromCharge($charge);
            }
            $successfulIds = $this->emailService->sendEmailList($chargeMails);

            Charge::whereIn("id", $successfulIds)
            ->update(['status' => self::STATUS_SENT]);

            LogRepository::info('...Finish e-mail sending');
        }catch(Exception $e){
            DB::rollBack();

            LogRepository::warning(
                'Could not send charge e-mails ' . 
                $e->getMessage()
            );
        }
    }
}