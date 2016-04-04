<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Savedxml extends Model
{
  protected $fillable = ['user_id', 'savedxml_name', 'xml'];
  
  protected $table = 'savedxml';
  
  public function user() {
    return $this->belongsTo('App\User');
  }
}
