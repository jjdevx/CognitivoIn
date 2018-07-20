<?php

namespace App\Http\Controllers\Api;

use App\Profile;
use App\Item;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AccountMovementController;
use Illuminate\Http\Request;
use DB;
use Swap\Laravel\Facades\Swap;

class ItemController extends Controller
{

    public function search(Profile $profile, $query)
    {
        //TODO add a search result for items
        if (strlen($query) > 3)
        {
            // code...
        }
    }

    public function CreateItem($data,Profile $profile)
    {
        $item = new Item();
        $item->profile_id = $profile->id;
        $item->sku = $data->code;
        $item->name = $data->name;
        $item->short_description = $data->comment;
        $item->unit_price = $data->unit_price;
        $item->currency = $data->currency_code ?? $profile->currency;

        $item->save();

    }

    public function sync(Request $request, Profile $profile)
    {
        $this->upload($request, $profile);
        $this->download($request, $profile);
    }

    public function upload(Request $request, Profile $profile)
    {

        $data = collect();

        if ($request->all() != [])
        {
            $data = collect($request->all());
        }

        $collection = json_decode($data->toJson());

        foreach ($collection as $key => $data)
        {
            $item = Item::where('id', $data->cloud_id)->first() ?? new Item();
            $item->profile_id = $profile->id;
            $item->sku = $data->code;
            $item->name = $data->name;
            $item->short_description = $data->comment;
            $item->unit_price = $data->unit_price;
            $item->currency = $data->currency_code ?? $profile->currency;


            $item->save();
        }
        return response()->json('Sucess');

    }

    public function download(Request $request,Profile $profile)
    {
        //Return a HTTP Resource from Laravel.
        $items = Item::where('profile_id',$profile->id)
        ->get();

        return response()->json($items);
    }
}
