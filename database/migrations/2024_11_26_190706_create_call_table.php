<?php

use App\Enums\CallType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('calls', function (Blueprint $table) {
            $table->id();
            $table->enum('type', CallType::values())->default('inbound');
            $table->integer('duration')->default(0); // Time in int (seconds)
            $table->string('notes')->nullable();
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
            $table->foreignId('agent_id')->constrained('agents')->cascadeOnDelete();
            $table->timestamps();

            $table->index('created_at');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('call');
    }
};
