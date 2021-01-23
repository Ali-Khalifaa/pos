<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTranslationsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tableNames = config('latevaweb-translatable.table_names');
        $columnNames = config('latevaweb-translatable.column_names');

        if (!Schema::hasTable($tableNames['translations'])) {
            Schema::create($tableNames['translations'], function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('field');
                $table->string('locale');
                $table->json('content')->nullable();
            });
        }

        if (!Schema::hasTable($tableNames['translatables'])) {
            Schema::create($tableNames['translatables'], function (Blueprint $table) use ($tableNames, $columnNames) {
                $table->unsignedBigInteger('translation_id');

                $table->string('translatable_type')->index();
                $table->unsignedBigInteger($columnNames['model_morph_key']);
                $table->index([$columnNames['model_morph_key'], 'translatable_type'], 'translatables_translatable_id_translatable_type_index');

                $table->foreign('translation_id')
                    ->references('id')
                    ->on($tableNames['translations'])
                    ->onUpdate('cascade')
                    ->onDelete('cascade');

                $table->primary(['translation_id', $columnNames['model_morph_key'], 'translatable_type'],
                    'translatables_translation_translatable_type_primary');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $tableNames = config('latevaweb-translatable.table_names');

        Schema::dropIfExists($tableNames['translatables']);
        Schema::dropIfExists($tableNames['translations']);
    }
}
