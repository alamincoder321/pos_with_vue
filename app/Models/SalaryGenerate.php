<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalaryGenerate extends Model
{
    use HasFactory;

    protected $guarded = ["id"];

    public function employee()
    {
        return $this->belongsTo(Employer::class, "employee_id", "id");
    }
}
