<?php

namespace Autovilla;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
	protected $primaryKey = 'item_id';

    protected $fillable = [
        'product_code', 'oem_part_no','brand','item','vehicle','model','make',
    ];

    public $timestamps = true;
}
