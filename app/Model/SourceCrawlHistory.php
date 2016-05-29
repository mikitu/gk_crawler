<?php

namespace GkCrawler\Model;

use Illuminate\Database\Eloquent\Model;

class SourceCrawlHistory extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'source_crawl_history';
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Get the source record associated.
     */
    public function source()
    {
        return $this->hasOne('GkCrawler\Model\Source');
    }
}
