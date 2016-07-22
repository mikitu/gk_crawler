<?php

namespace GkCrawler\Model;

use Illuminate\Database\Eloquent\Model;

class Embassy extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'embassy';

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
        'title', 'embassy_name', 'embassy_of', 'embassy_of_country_id',
        'embassy_in', 'embassy_in_country_id',
        'address', 'city', 'postcode', 'phone', 'fax', 'email',
        'website', 'office_hours', 'details', 'latitude', 'longitude'
    ];
}
