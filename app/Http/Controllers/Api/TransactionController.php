<?php

namespace App\Http\Controllers\Api;

use App\Item;
use App\Relationship;
use App\Profile;
use App\Order;
use App\Vat;
use App\VatDetail;
use App\OrderDetail;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AccountMovementController;
use Illuminate\Http\Request;
use DB;
use Swap\Laravel\Facades\Swap;

class TransactionController extends Controller
{

    public function SalesInvoice_createApprove(Request $request,Profile $profile)
    {
        $data = $request[0];

        if (isset($data) == false)
        {
            $data = $request;
        }

        //Run Validations and prevent malitious users from inserting data that is not supposed to be entered.

        $order = new Order();

        $order->number = $data['number'];
        $order->relationship_id = $data['relationship_id'];
        $order->code = $data['range_code'];
        $order->location_id = $data['location_id'];

        if ($data['contract_id'] > 0)
        {
            $order->contract_id = $data['contract_id'];
        }

        $order->code_expiry =$data['code_expiry'] ? Carbon::parse($data['code_expiry']):null;

        //TODO. wrong. let front end decide if it is printed or not.
        $order->is_printed = $data['isPrinted'] ?? false;
        $order->is_impex = $data['isImpex'] ?? false;

        $order->date = $data['date'] ?? Carbon::now();
        $order->currency = $data['currency'] ?? $profile->currency;
        $order->currency_rate = ($data['rate'] ?? Swap::latest($profile->currency . '/' . $order->currency)->getValue()) ?? 1;
        $order->save();

        foreach ($data['details'] as $data_detail)
        {
            $item = Item::where('id', $data_detail['id'])->select('id', 'vat_id')->first();
            $detail = new OrderDetail();
            $detail->order_id = $order->id;
            $detail->item_id = $item->id;

            $detail->item_sku = $data_detail['sku'];
            $detail->item_name = $data_detail['name'];
            $detail->quantity = (double)$data_detail['quantity'];
            $detail->vat_id = $item->vat_id;
            $detail->unit_price = (double)$data_detail['unit_price'];
            $detail->save();
        }

        $orderController = new OrderController();
        $orderController->approve($order->id);
        //
        $accountMovement = new AccountMovementController();
        $accountMovement->makePayment($request, $order->id);

        $vatDetail = VatDetail::leftjoin('order_details', 'order_details.vat_id', 'vat_details.vat_id')
        ->where('order_id', $order->id)
        ->select(DB::raw('CONCAT(round(max(coefficient),2) * 100, "%" )as coefficient'),
        DB::raw('round(sum(percent * coefficient * unit_price * quantity),2) as value')
        )
        ->groupBy('coefficient')
        ->get();

        $data2 = [];
        $data2[] = [
            'Date' => $order->date->format('d-m-Y'),
            'Detail'=> $vatDetail
        ];

        return response()->json($data2);
    }

    // TODO: Make chunks of data. learn from debehaber
    public function uploadOrder (Request $request, Profile $profile)
    {
        $data = collect();

        if ($request->all() != [])
        {
            $data = collect($request->all());
        }

        $collection = json_decode($data->toJson());

        foreach ($collection as $key => $data)
        {
            //Check to see if cloud id exists in system
            $order = Order::mySales()->where('id', $data->cloud_id)->with('details')->first();

            //If Exists == false, create Order, link Relationship and use.
            if(isset($order) == false)
            {
                $order = new Order();
                $order->relationship_id = $this->checkCreateRelationships($profile, $data)->id;
            }

            //fill up data regardless if exists or not. this will allow new data to prevail.
            $data->customer->cloud_id=$order->relationship_id;
            $this->loadData_Order($order, $data);
        }

        return response()->json($collection);
    }

    // This function will create or update an existing Order with the new data inserted.
    public function loadData_Order ($order, $data)
    {
        $profile = request()->route('profile');
        $order->ref_id = $data->my_id;
        $order->number = $data->number;
        $order->is_printed = $data->number != "" ? true : false;
        $order->trans_date =$this->convert_date($data->trans_date);
        $order->credit_days = $data->credit_days;
        $branch = Location::where('profile_id', $profile->id)->where('name', $data->branch_name)->first();

        if (!isset($branch)) {
            $branch = new Location();
            $branch->profile_id = $profile->id;
            $branch->name = $data->branch_name;
            $branch->save();
        }

        $order->location_id = $branch->id;

        //$currency = Currency::first();
        $order->currency_id = $data->currency_code;
        $order->currency_rate = $data->currency_rate;
        $order->comment = $data->comment;

        $order->save();

        //assign id to collection.
        $data->ref_id = $order->id;

        //Is it possible to use $order instead of getting fresh data from db?
        //$order = Order::where('id', $order->id)->with('details')->first();

        foreach ($data->details as $data_detail)
        {
            $detail = $order->details->where('ref_id', $data->my_id)->first(); //OrderDetail::where('ref_id', $id_ref)->first();

            if(isset($detail) == false)
            {
                $detail = new OrderDetail();
                $detail->order_id = $order->id;
            }

            $item = $this->createOrUpdate_Item($data_detail->item);

            $detail->item_id = $item->id;
            $detail->ref_id = $data_detail->my_id;
            $detail->quantity = $data_detail->quantity;
            $detail->unit_price = $data_detail->price;
            //$detail->discount = $data->discount;
            $detail->save();
            $data_detail->ref_id=$detail->id;
        }
    }

    public function syncTransactionStatus(Request $request, Profile $profile)
    {
        $collection = collect();

        if ($request->all() != [])
        {
            $transactions = $request->all();
            $collection = collect($transactions);
        }

        $collection = json_decode($collection->toJson());
        foreach ($collection as $key => $element)
        {
            $transaction=$this->checkOrder($element->id_ref);
            if(isset($transaction))
            {
                $transaction->ref_id = $element->id_sales_invoice;
                $transaction->save();
                $orderstatus = $this->UpdateDetail($transaction,$element->details);
                if (isset($orderstatus))
                {
                    $orderstatus->status = 3;
                    $orderstatus->save();
                }
                else
                {
                    $orderstatus = new OrderStatus();
                    $orderstatus->order_id = $order->id;
                    $orderstatus->status = 3;
                    $orderstatus->save();
                }
            }
        }
    }

    public function downloadOrder (Request $request, Profile $profile)
    {
        $orders = Order::FromCustomers()
        ->leftJoin('order_status','orders.id','=','order_status.order_id')
        ->with('details')
        ->whereNull('ref_id')
        ->where('order_status.status','=',2)
        ->select('orders.id','orders.ref_id','orders.location_id',
        'orders.classification_id','orders.recurring_order_id','orders.buyer_profile_id',
        'orders.salesrep_profile_id','orders.relationship_id','customer_alias'
        ,'customer_taxid','customer_address','customer_telephone','customer_email')
        ->get();
        return response()->json($orders);
    }
}
