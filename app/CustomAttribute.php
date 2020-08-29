<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomAttribute extends Model
{
	public $timestamps = false;

    protected $fillable = [
        'contact_id', 'key', 'value'
    ];
}
