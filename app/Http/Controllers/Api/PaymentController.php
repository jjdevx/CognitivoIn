<?php

namespace App\Http\Controllers\Api;

use App\Profile;
use App\VatDetail;
use App\ Order;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use App\Http\Controllers\AccountMovementController;
use Illuminate\Http\Request;
use DB;
use Swap\Laravel\Facades\Swap;

class PaymentController extends Controller
{


    public function upload(Request $request, Profile $profile)
    {

        $accountMovement = new AccountMovementController();
        $accountMovement->makePayment($request);
        $order=Order::find($request->id);
        $vatDetail = VatDetail::leftjoin('order_details', 'order_details.vat_id', 'vat_details.vat_id')
        ->where('order_id', $request->id)
        ->select(DB::raw('CONCAT(round(max(coefficient),2) * 100, "%" )as coefficient'),
        DB::raw('round(sum(percent * coefficient * unit_price * quantity),2) as value')
        )
        ->groupBy('coefficient')
        ->get();

        $data2 = [];
        $data2[] = [
           'currency'=>$order->currency,
           'rate'=>$order->currency_rate,
            'date' => $order->date,
            'Detail'=> $vatDetail
        ];
return response()->json($data2);

    }

}
