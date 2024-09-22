<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = ['first_name', 'last_name'];

    public function phoneNumbers()
    {
        return $this->hasMany(PhoneNumber::class);
    }
}
