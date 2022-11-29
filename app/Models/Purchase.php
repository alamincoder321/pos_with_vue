<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = ["*"];

    public function purchase(){
        return $this->hasMany(PurchaseDetails::class, "purchase_id", "id");
    }
}
