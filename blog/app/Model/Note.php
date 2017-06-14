<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    protected $table = 'notes';

    protected $primaryKey = 'notesID';

    // Remove all timestamps
    public $timestamps = false;

	// Saving only the uploaded at time
 //    public static function boot()
 //    {
	//     static::creating( function ($model) {
	//         $model->setCreatedAt($model->freshTimestamp());
	//     });

	// }
	// define column for created at
    // const CREATED_AT = 'uploadedDateTime';
    // 
    protected $fillable = ['companyID'];
}