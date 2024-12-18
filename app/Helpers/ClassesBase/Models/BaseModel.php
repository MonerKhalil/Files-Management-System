<?php

namespace App\Helpers\ClassesBase\Models;

use App\Helpers\MyApp;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class BaseModel extends Model
{
    use SoftDeletes,HasFactory;

    protected $guarded = ["id"];

    protected static function boot()
    {
        static::creating(function ($model) {
            $user = MyApp::Classes()->user->get();
            if (!is_null($user)) {
                $model->created_by = $user->id;
            }
        });
        static::updating(function ($model) {
            $user = MyApp::Classes()->user->get();
            if (!is_null($user)) {
                $model->updated_by = $user->id;
            }
        });
        parent::boot(); // TODO: Change the autogenerated stub
    }

    public function userCreatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, "created_by", "id");
    }

    public function userUpdatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, "updated_by", "id");
    }
}
