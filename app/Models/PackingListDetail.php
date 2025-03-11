<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackingListDetail extends Model
{
    use HasFactory;

    protected $table = 'packing_list_details';
    protected $fillable = ['packing_master_id', 'product_id', 'packaging', 'weight', 'par', 'total'];

    public function master()
    {
        return $this->belongsTo(PackingListMaster::class, 'packing_master_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function packingList()
{
    return $this->belongsTo(PackingListMaster::class, 'packing_master_id');
}
}
