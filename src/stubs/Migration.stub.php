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
        Schema::create('{{snakeCasePluralName}}', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->tinyInteger('status')->default({{capitalCaseSingularName}}::getStatusActive())->index('status');

            $table->timestamp('created_at')->nullable();
            $table->unsignedBigInteger('created_by')->default(0);
            $table->timestamp('updated_at')->nullable();
            $table->unsignedBigInteger('updated_by')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('{{snakeCasePluralName}}');
    }
};
