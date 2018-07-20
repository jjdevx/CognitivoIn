<?php

namespace App\Http\Controllers\Api;

use App\Profile;
use App\Relationship;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Swap\Laravel\Facades\Swap;

class CustomerController extends Controller
{

    public function search(Profile $profile, $query)
    {
        //TODO add a search result for items
        if (strlen($query) > 3)
        {
            // code...
        }
    }

    public function CreateCustomer($data,Profile $profile)
    {
        $relationship = new Relationship();
        $relationship->supplier_id = $profile->id;
        $relationship->supplier_accepted = true;

        $relationship->customer_taxid = $data->govcode;
        $relationship->customer_alias = $data->alias;
        $relationship->customer_address = $data->address;
        $relationship->customer_telephone = $data->telephone;
        $relationship->customer_email = $data->email;
        $relationship->credit_limit = $data->credit_limit ?? 0;
        $relationship->contract_ref = $data->contract_ref ?? 0;

        $relationship->save();
        return $relationship;
    }

    public function sync(Request $request, Profile $profile)
    {
        $rsp = $this->upload($request, $profile);
        $this->download($request, $profile);

        return $rsp;
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
            $relationship = Relationship::where('id', $data->cloud_id)->first() ?? new Relationship();
            $relationship->supplier_id = $profile->id;
            $relationship->supplier_accepted = true;

            $relationship->customer_taxid = $request->customer_taxid;
            $relationship->customer_alias = $request->customer_alias;
            $relationship->customer_address = $request->customer_address;
            $relationship->customer_telephone = $request->customer_telephone;
            $relationship->customer_email = $request->customer_email;
            $relationship->credit_limit = $request->credit_limit ?? 0;
            $relationship->contract_ref = $request->contract_ref ?? 0;

            $relationship->save();
        }

        return response()->json('Done!', 200);
    }

    public function download(Request $request,Profile $profile)
    {
        //Return a HTTP Resource from Laravel.
        $items = Relationship::where('profile_id',$profile->id)
        ->get();

        return response()->json($items);
    }
}