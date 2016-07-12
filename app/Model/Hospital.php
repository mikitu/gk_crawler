<?php

namespace GkCrawler\Model;

use Illuminate\Database\Eloquent\Model;

class Hospital extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'hospital';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'address', 'city', 'zipcode', 'phone', 'latitude', 'longitude', 'raw_data', 'country', 'city_id', 'country_id'
    ];

//    public function country()
//    {
//        return $this->belongsTo('GkCrawler\Model\Country');
//    }
}
