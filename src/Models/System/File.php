<?php

namespace Shopper\Framework\Models\System;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class File extends Model
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
        'is_public',
        'position',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'is_public',
        'position'
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'file_path',
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
     * Get the file full path.
     *
     * @return \Illuminate\Contracts\Routing\UrlGenerator|string
     */
    public function getFilePathAttribute()
    {
        return Storage::disk(config('shopper.storage.disks.uploads'))->url($this->file_name);
    }

    /**
     * Get all of the owning filetable models.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function filetable()
    {
        return $this->morphTo();
    }
}
