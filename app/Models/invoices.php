<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\sections;
class invoices extends Model
{
    use HasFactory, softDeletes;

    protected $fillable = [
        'invoice_number',
        'invoice_date',
        'due_date',
        'section_id',
        'product',
        'amount_collection',
        'amount_commission',
        'discount',
        'rate_vat',
        'value_vat',
        'total',
        'status',
        'value_status',
        'note',
        'user_id',
    ];

    public function section()
    {
        return $this->belongsTo(Sections::class, 'section_id','id');
    }

    public function scopeFilter(Builder $builder, $filters)
    {
        $builder->when($filters['status'] ?? false, function ($builder, $value) {
            $builder->where('invoices.status',$value);
        });
        if($filters['invoice_number'] ?? false){
            $builder->where('invoice_number',$filters['invoice_number']);
        }
        if($filters['start_at'] ?? false){
            $builder->whereDate('invoice_date','>=',$filters['start_at']);
        }
        if($filters['end_at'] ?? false){
            $builder->whereDate('due_date','<=',$filters['end_at']);
        }
    }
    public function scopeSearch(Builder $builder, $filters)
    {
        $builder->when($filters['section_id'] ?? false, function ($builder, $value) {
            $builder->where('invoices.section_id',$value);
        });
        if($filters['product'] ?? false){
            $builder->where('product',$filters['product']);
        }
        if($filters['start_at'] ?? false){
            $builder->whereDate('invoice_date','>=',$filters['start_at']);
        }
        if($filters['end_at'] ?? false){
            $builder->whereDate('due_date','<=',$filters['end_at']);
        }
    }
}
