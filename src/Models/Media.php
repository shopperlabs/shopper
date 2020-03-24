<?php

namespace Shopper\Framework\Models;

use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'disk_name',
        'file_name',
        'file_size',
        'content_type',
        'file_url',
        'field',
        'mediatable_type',
        'mediatable_id',
        'is_public',
        'sort_order'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'mediatable_type',
        'mediatable_id',
        'is_public',
        'sort_order'
    ];

    /**
     * Get all of the owning mediatable models.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function mediatable()
    {
        return $this->morphTo();
    }

    /**
     * Get the table associated with the model.
     *
     * @return string
     */
    public function getTable()
    {
        return shopper_table('media');
    }
}
