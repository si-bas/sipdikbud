<?php

namespace App\Models\Download;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Collection extends Model
{
    use SoftDeletes;

    protected $table = 'collection_downloads';
    protected $fillable = [
        "collection_id",
        "user_id",
        "reason_text",
        "reason_id",
    ];

    public function reason()
    {
        return $this->belongsTo('App\Models\Download\Reason', 'reason_id', 'id');
    }

    public function collection()
    {
        return $this->belongsTo('App\Models\Collection\Collection', 'collection_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }
}
