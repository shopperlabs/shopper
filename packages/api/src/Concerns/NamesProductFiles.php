<?php

declare(strict_types=1);

namespace Shopper\Api\Concerns;

use Illuminate\Support\Str;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\HeaderUtils;

trait NamesProductFiles
{
    protected function contentDisposition(Media $file): string
    {
        $name = str_replace(['/', '\\'], '-', (string) preg_replace('/[\x00-\x1F\x7F]/u', '', $file->name));

        if (filled($file->extension)) {
            $name = Str::finish($name, '.'.$file->extension);
        }

        $fallback = str_replace('%', '', Str::ascii($name));

        return HeaderUtils::makeDisposition(
            HeaderUtils::DISPOSITION_ATTACHMENT,
            $name,
            $fallback !== '' ? $fallback : 'download',
        );
    }
}
