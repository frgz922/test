<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UrlsAliases extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'urls_aliases';

    /**
     * @var array
     */
    protected $fillable = ['url_id', 'alias'];

    public function url(){
        return $this->belongsTo('App\Urls','url_id','id');
    }
}
