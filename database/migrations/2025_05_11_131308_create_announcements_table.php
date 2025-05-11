<?php

use App\Models\Category;
use App\Models\Tutor;
use App\Models\User;
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
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Tutor::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Category::class)->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('description');
            $table->enum('operation_type', ['buy', 'don', 'exchange']);
            $table->double('price')->nullable();
            $table->enum('state', ['neuf', 'comme_neuf', 'bon_etat', 'usage', 'abime']);
            $table->boolean('is_completed')->default(false);
            $table->boolean('is_canceled')->default(false);
            $table->text('exchange_location_address');
            $table->decimal('exchange_location_longt');
            $table->decimal('exchange_location_lat');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('announcements');
    }
};
