<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StripeWebhookController extends Controller
{
    
    public function handle(Request $request){

        $payload = $request->getContent();

        $event = null;


        try {
            $event = \Stripe\Event::constructFrom(
                json_decode($payload, true)
            );
        }

        catch(\UnexpectedValueException $e){
            return response()->json(['error'=>'Invalid Payload'], 400);
        }


        if($event->type === 'invoice.paid'){
            $invoice = $event->data->object;
            $customerID = $invoice->customer;

            $this->createFinalInvoice($customerID);
        }

        return response()->json(['status'=>'succes'], 200);
    }


    private function createFinalInvoice($customerID){

        $stripe = new \Stripe\StripeClient(env('STRIPE_CUSTOMER_ID'));

        $trip = TripsModel::findOrFail($tripID);

        $finalPaymentAmount = $trip->tripPrice * 0.40;

        $stripe->invoiceItems->create([
            'customer' => $customerID,
            'amount' => $finalPaymentAmount * 100,
            'currency' => 'usd',
            'description' => 'Final Payment for '.$trip->tripName,
        ]);

        $finalInvoice = $stripe->invoices->create([
            'customer'=>$customerID,
            'collection_method'=>'send_invoice',
            'auto_advance'=>true,
            'days_until_due'=> 7,
        ]);

        $stripe->invoices->finalizeInvoice($finalInvoice->id);

    }
}
