<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UrlLog extends Model
{
    protected $table = 'app_urls_log';
    protected $fillable = ['batch', 'url', 'complete', 'success'];
}
