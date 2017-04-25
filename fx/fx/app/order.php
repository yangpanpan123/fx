<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class order extends Model
{
    protected $primaryKey = 'oid';
    public $timestamps = false;
}
