<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('sizes', function (Blueprint $table) {

            $table->string('slug')
                ->after('name')
                ->nullable();
            $table->integer('neck')
                ->after('slug')
                ->nullable();
            $table->integer('shoulder')
                ->after('neck')
                ->nullable();
            $table->integer('chest')
                ->after('shoulder')
                ->nullable();
            $table->integer('waist')
                ->after('chest')
                ->nullable();
            $table->integer('hip')
                ->after('waist')
                ->nullable();
            $table->integer('arm_hole')
                ->after('hip')
                ->nullable();
            $table->integer('back_length_88')
                ->after('arm_hole')
                ->nullable();
            $table->integer('back_length_89')
                ->after('back_length_88')
                ->nullable();
            $table->integer('aloha_88')
                ->after('back_length_89')
                ->nullable();
            $table->integer('aloha_89')
                ->after('aloha_88')
                ->nullable();
            $table->integer('cuffs_circle')
                ->after('aloha_89')
                ->nullable();
            $table->integer('short_sleeve')
                ->after('cuffs_circle')
                ->nullable();
            $table->integer('sleeve_circle')
                ->after('short_sleeve')
                ->nullable();
            $table->enum('body_type', ['PM2','PM3','PM4','PM5','PM7'])
                ->after('sleeve_circle')
                ->default('PM2');
            $table->enum('sleeve_type', ['regular','slim'])
                ->after('body_type')
                ->default('regular');
            $table->integer('fabric_needed')
                ->after('sleeve_type')
                ->nullable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sizes', function (Blueprint $table) {
            $table->dropColumn('slug');
            $table->dropColumn('neck');
            $table->dropColumn('shoulder');
            $table->dropColumn('chest');
            $table->dropColumn('waist');
            $table->dropColumn('hip');
            $table->dropColumn('arm_hole');
            $table->dropColumn('back_length_88');
            $table->dropColumn('back_length_89');
            $table->dropColumn('aloha_88');
            $table->dropColumn('aloha_89');
            $table->dropColumn('cuffs_circle');
            $table->dropColumn('short_sleeve');
            $table->dropColumn('sleeve_circle');
            $table->dropColumn('body_type');
            $table->dropColumn('sleeve_type');
            $table->dropColumn('fabric_needed');
        });
    }
};
