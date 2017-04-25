<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class user extends Model
{
    protected $primaryKey = 'uid';
    public $timestamps = false;
}
