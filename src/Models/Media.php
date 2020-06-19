<?php

namespace Shopper\Framework\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

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
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'image_full_path',
    ];

    /**
     * Get the table associated with the model.
     *
     * @return string
     */
    public function getTable()
    {
        return shopper_table('media');
    }

    /**
     * Get The images link.
     *
     * @return \Illuminate\Contracts\Routing\UrlGenerator|string
     */
    public function getImageFullPathAttribute()
    {
        return Storage::disk(config('shopper.storage.disks.uploads'))->url($this->file_url);
    }

    /**
     * Get all of the owning mediatable models.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function mediatable()
    {
        return $this->morphTo();
    }
}
