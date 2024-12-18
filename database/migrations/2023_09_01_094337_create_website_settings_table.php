<?php

use App\Helpers\ClassesBase\TypesFieldsEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('website_settings', function (Blueprint $table) {
            $table->id();
            $table->string("key")->unique();
            $table->enum("type", Arr::except(TypesFieldsEnum::values(),$this->getExcept()))->default(TypesFieldsEnum::TEXT->value);
            $table->boolean("is_required")->default(true);
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
        Schema::dropIfExists('website_settings');
    }

    private function getExcept(){
        return[TypesFieldsEnum::RELATION->value,TypesFieldsEnum::ENUM->value];
    }
};
