<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    use HasFactory;

    protected $fillable = [
        'code', 'description', 'type', 'amount', 'min_purchase_amount', 'start_time', 'end_time'
    ];

    // Definisikan enum untuk tipe diskon
    public const TYPE_PERCENTAGE = 'percentage';
    public const TYPE_FIXED = 'fixed';
    public const TYPE_TIME_BASED = 'time_based';

    // Definisikan relasi dengan model Product jika diperlukan
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
