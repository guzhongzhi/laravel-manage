<?php namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class HotelImage extends Model {
	protected $table = "hotel_image";
    protected $fillable = [
        'id',
        'hotel_id',
        'url',
        'created_at',
        'updated_at',
    ];
}
