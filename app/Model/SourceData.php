<?php

namespace GkCrawler\Model;

use Illuminate\Database\Eloquent\Model;

class SourceData extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'source_data';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'source_id', 'city_id', 'country_id', 'address', 'phone', 'zipcode', 'latitude', 'longitude',
    ];

    public function source()
    {
        return $this->belongsTo('GkCrawler\Model\Source');
    }

    public function country()
    {
        return $this->belongsTo('GkCrawler\Model\Country', 'country_code', 'country_code');
    }

    public function city()
    {
        return $this->belongsTo('GkCrawler\Model\City');
    }
}
