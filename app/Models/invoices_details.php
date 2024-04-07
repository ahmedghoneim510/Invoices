<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class invoices_details extends Model
{
    use HasFactory;
    protected $fillable = [
        'invoice_id',
        'invoice_number',
        'product',
        'section',
        'status',
        'value',
        'note',
        'user_id',
        ];
    public function invoice(){
        return $this->belongsTo('App\Models\invoices');
    }
    public function user(){
        return $this->belongsTo('App\Models\User');
    }
}
