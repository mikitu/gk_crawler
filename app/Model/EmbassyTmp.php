<?php

namespace GkCrawler\Model;

use Illuminate\Database\Eloquent\Model;

class EmbassyTmp extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'embassy_tmp';

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
        'country', 'url', 'done', 'source'
    ];
}
