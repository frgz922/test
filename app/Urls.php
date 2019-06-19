<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Urls extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'urls';

    /**
     * @var array
     */
    protected $fillable = ['title', 'url'];

    public function alias(){
        return $this->belongsTo('App\UrlsAliases','id')->select('id', 'shortened_url');
    }
}
