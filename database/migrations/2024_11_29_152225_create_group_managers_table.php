<?php

use App\Models\GroupManager;
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
        Schema::create('group_managers', function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_id")->constrained("users")->cascadeOnDelete();
            $table->foreignId("group_id")->nullable()->constrained("group_managers")->cascadeOnDelete();
            $table->string("name");
            $table->unique(["name","user_id"]);
            $table->enum("type", GroupManager::TYPES)->default("private");
            $table->text("description")->nullable();
            $table->text("url_generate")->unique()->nullable();
            $table->boolean("denied_from_delete")->default(false);
            $table->foreignId("created_by")->nullable()->constrained("users")->cascadeOnDelete();
            $table->foreignId("updated_by")->nullable()->constrained("users")->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('group_managers');
    }
};
