<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class Upload extends Model
{

	public $timestamps = false;
	protected $fillable = ['path','x','y','width','height'];
	
}