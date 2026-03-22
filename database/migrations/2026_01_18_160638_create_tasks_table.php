<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();

            $table->string('title');
            $table->enum('state', ['open', 'closed', 'canceled'])->default('open');
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();
            $table->date('planned_for')->nullable();
            $table->date('deadline')->nullable();
            $table->text('notes')->nullable();
            $table->json('checklist')->nullable();
            $table->nullableMorphs('parent_list');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
