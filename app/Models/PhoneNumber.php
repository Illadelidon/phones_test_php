<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhoneNumber extends Model
{
    use HasFactory;

    protected $fillable = ['contact_id', 'phone_number'];

    public function Contact()
    {
        return $this->belongsTo(Contact::class);
    }
}
