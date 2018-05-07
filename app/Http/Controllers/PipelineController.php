<?php

namespace App\Http\Controllers;

use App\Profile;
use App\Pipeline;
use App\PipelineStage;
use Illuminate\Http\Request;

class PipelineController extends Controller
{
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index(Profile $profile)
    {
        $pipelines = Pipeline::with('stages')->Pipelines($profile->id)->get();
        return view('company.sales.opportunities.pipeline.list')->with('pipelines',$pipelines);
    }

    public function list_pipelines(Profile $profile,$skip)
    {

      $pipelines = Pipeline::with('stages')->Pipelines($profile->id)->skip($skip)
        ->take(100)->get();

      return response()->json($pipelines);
    }
    public function get_pipelines(Profile $profile,$skip)
    {

      $pipelines = Pipeline::with('stages')->Pipelines($profile->id)->get();

      return response()->json($pipelines);
    }
    public function list_pipelinesByID(Profile $profile,$id)
    {


      $pipelines = Pipeline::with('stages')->Pipelines($profile->id)

      ->where('id',$id)

        ->get();


      return response()->json($pipelines);
    }
    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function create(Profile $profile)
    {
        return view('company.sales.opportunities.pipeline.form');
    }

    /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function store(Profile $profile,Request $request)
    {

        $pipeline =$request->id == 0 ? new Pipeline() :
        Pipeline::where('id',$request->id)->first();

        $pipeline->profile_id = $profile->id;
        $pipeline->name = $request->name;
        $pipeline->is_active = true;

        $pipeline->save();

          return response()->json('ok',200);

    }

    /**
    * Display the specified resource.
    *
    * @param  \App\Pipeline  $pipeline
    * @return \Illuminate\Http\Response
    */
    public function show(Profile $profile, Pipeline $pipeline)
    {
        //
    }

    /**
    * Show the form for editing the specified resource.
    *
    * @param  \App\Pipeline  $pipeline
    * @return \Illuminate\Http\Response
    */
    public function edit(Profile $profile, Pipeline $pipeline)
    {
        $pipelinestages = PipelineStage::with('pipeline')->where('pipeline_id', $pipeline->id)->orderBy('sequence')->get();

        return view('company.sales.opportunities.pipeline.form')
        ->with('pipeline',$pipeline)
        ->with('pipelinestages',$pipelinestages);
    }

    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \App\Pipeline  $pipeline
    * @return \Illuminate\Http\Response
    */
    public function update(Request $request, Profile $profile, Pipeline $pipeline)
    {
        $pipeline->name = $request->name;

        $pipeline->is_active = true;

        $pipeline->save();

        $pipelinestages = PipelineStage::with('pipeline')->orderBy('sequence')->get();

        return view('company.sales.opportunities.pipeline.form')
        ->with('pipeline',$pipeline)
        ->with('pipelinestages',$pipelinestages);
    }

    /**
    * Remove the specified resource from storage.
    *
    * @param  \App\Pipeline  $pipeline
    * @return \Illuminate\Http\Response
    */
    public function destroy(Profile $profile, Pipeline $pipeline)
    {
        $pipeline->delete();

        $pipelines = Pipeline::with('stages')->Pipelines($profile->id)->get();

        return view('company.sales.opportunities.pipeline.list')->with('pipelines',$pipelines);
    }
}