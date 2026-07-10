<?php

declare(strict_types=1);

namespace Shopper\Core\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Model;
use Shopper\Core\Enum\ImportStatus;
use Shopper\Core\Models\Traits\HasPublicId;

/**
 * @property-read int $id
 * @property-read ?string $public_id
 * @property-read string $source
 * @property-read string $disk
 * @property-read string $file_path
 * @property-read ImportStatus $status
 * @property-read int $total_products
 * @property-read int $imported_count
 * @property-read int $failed_count
 * @property-read array<string, string>|null $mapping
 * @property-read array<int, array{handle: string, message: string}>|null $errors
 * @property-read ?string $batch_id
 * @property-read ?int $user_id
 * @property-read ?CarbonInterface $started_at
 * @property-read ?CarbonInterface $finished_at
 * @property-read CarbonInterface $created_at
 * @property-read CarbonInterface $updated_at
 */
class ProductImport extends Model
{
    use HasPublicId;

    protected $guarded = [];

    public function getTable(): string
    {
        return shopper_table('product_imports');
    }

    protected function casts(): array
    {
        return [
            'status' => ImportStatus::class,
            'mapping' => 'array',
            'errors' => 'array',
            'total_products' => 'integer',
            'imported_count' => 'integer',
            'failed_count' => 'integer',
            'started_at' => 'datetime',
            'finished_at' => 'datetime',
        ];
    }
}
