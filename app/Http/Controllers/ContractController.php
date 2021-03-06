<?php

namespace App\Http\Controllers;

use App\Contract;
use App\ContractDetail;
use App\Profile;
use App\Http\Resources\ContractResource;
use DB;
use Illuminate\Http\Request;

class ContractController extends Controller
{
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index(Profile $profile, $filterBy)
    {
        if ($filterBy == 1)
        {
            return ContractResource::collection(Contract::paginate(2));
        }
        else if($filterBy == 2)
        {
            return ContractResource::collection(Contract::onlyTrashed()->paginate(100));

        }
        else if($filterBy == 3)
        {
            return ContractResource::collection(Contract::
            join('contract_details', 'contract_details.contract_id', 'contracts.id')
            ->where('contract_details.percent' ,'>', 0)
            ->where('contracts.profile_id' ,$profile->id)
            ->groupBy('contracts.id')
            ->select(DB::raw('max(contracts.id) as id'),
            DB::raw('max(contracts.name) as name'))
            ->paginate(25));
        }

        return response()->json($contract);
    }

    /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function store(Profile $profile, Request $request)
    {
        $contract = Contract::where('id', $request->id)->first() ?? new Contract();

        $contract->name = $request->name;
        $contract->profile_id = $profile->id;
        $contract->country = $profile->country;
        $contract->save();

        $totalPercent = 0;
        $details = collect($request->details);

        foreach ($details as $row)
        {
            $detail = ContractDetail::where('id', $row['id'])->first()
            ?? new ContractDetail();
            $detail->contract_id = $contract->id;
            $detail->percent =$row['percent'];
            $detail->offset = $row['offset'];
            $detail->save();

            $totalPercent += $detail->percent;
        }
        //this code adds the remaining balance to the end.
        $contract_detail=$contract->details()->orderBy('id', 'DESC')->first();
        if ($totalPercent < 1 && isset($contract_detail))
        {
            $detail = $contract->details()->orderBy('id', 'DESC')->first();
            $detail->percent = $detail->percent + (1 - $totalPercent);
            $detail->save();
        }

        return response()->json('Ok', 200);
    }

    /**
    * Show the form for editing the specified resource.
    *
    * @param  \App\Contract  $contract
    * @return \Illuminate\Http\Response
    */
    public function edit(Profile $profile, Contract $contract)
    {
        $contract = Contract::where('id', $contract->id)
        ->with('details')
        ->first();

        return response()->json($contract);
    }

    /**
    * Remove the specified resource from storage.
    *
    * @param  \App\Contract  $contract
    * @return \Illuminate\Http\Response
    */
    public function destroy(Profile $profile, Contract $contract)
    {
        if ($contract->profile_id == $profile->id)
        {
            $contract->delete();
            return response()->json('200', 200);
        }

        return response()->json('Resource not found', 401);
    }

    /**
    * Remove the specified resource from storage.
    *
    * @param  \App\Contract  $contract
    * @return \Illuminate\Http\Response
    */
    public function restore(Profile $profile, Contract $contract)
    {
        if ($contract->profile_id == $profile->id)
        {
            $contract->restore();
            return response()->json('200', 200);
        }

        return response()->json('Resource not found', 401);
    }
}
