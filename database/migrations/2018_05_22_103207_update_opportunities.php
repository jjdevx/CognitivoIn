<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateOpportunities extends Migration
{
    /**
    * Run the migrations.
    *
    * @return void
    */
    public function up()
    {
        Schema::table('opportunities', function (Blueprint $table)
        {
            $table->unsignedInteger('relationship_id')->nullable()->change();

            $table->dropForeign(['pipeline_stage_id']);
            $table->dropColumn(['pipeline_stage_id']);

            $table->unsignedInteger('pipeline_id')->nullable()->after('relationship_id');
            $table->foreign('pipeline_id')->references('id')->on('pipelines')->onDelete('cascade');

            $table->string('name')->nullable()->after('deadline_date');
        });

        Schema::table('opportunity_tasks', function (Blueprint $table)
        {
            $table->unsignedInteger('pipeline_stage_id')->nullable()->after('opportunity_id');
            $table->foreign('pipeline_stage_id')->references('id')->on('pipeline_stages')->onDelete('cascade');

            $table->timestamp('completed_at')->nullable()->after('completed');
        });

        Schema::table('contract_details', function (Blueprint $table)
        {
            $table->unsignedDecimal('percent', 5, 4)->change();
        });

        Schema::table('pipeline_stages', function (Blueprint $table)
        {
            $table->unsignedTinyInteger('activity_type')->nullable()->after('sequence');
        });
    }

    /**
    * Reverse the migrations.
    *
    * @return void
    */
    public function down()
    {
        Schema::table('opportunities', function (Blueprint $table)
        {
            $table->unsignedInteger('pipeline_stage_id')->nullable()->after('relationship_id');
            $table->foreign('pipeline_stage_id')->references('id')->on('pipeline_stages')->onDelete('cascade');

            $table->dropForeign(['pipeline_id']);
            $table->dropColumn(['pipeline_id']);

            $table->dropColumn(['name']);
        });

        Schema::table('opportunity_tasks', function (Blueprint $table)
        {
            $table->dropForeign(['pipeline_stage_id']);
            $table->dropColumn(['pipeline_stage_id']);

            $table->dropColumn(['completed_at']);
        });

        Schema::table('pipeline_stages', function (Blueprint $table)
        {
            $table->dropColumn(['activity_type']);
        });
    }
}