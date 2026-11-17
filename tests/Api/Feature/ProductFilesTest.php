<?php

declare(strict_types=1);

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;
use Shopper\Core\Enum\PaymentStatus;
use Shopper\Core\Enum\ProductType;
use Shopper\Core\Models\Order;
use Shopper\Core\Models\OrderItem;
use Tests\Core\Stubs\Product;
use Tests\Core\Stubs\User;

uses(Tests\Api\TestCase::class);

beforeEach(function (): void {
    Storage::fake('local');
    Storage::fake('public');

    $this->customer = User::factory()->create();

    $this->product = Product::factory()->publish()->create(['type' => ProductType::Virtual]);

    $this->file = $this->product
        ->addMedia(UploadedFile::fake()->createWithContent('ebook.txt', 'paid content'))
        ->toMediaCollection('files');
});

function orderedFile(Product $product, array $order = []): OrderItem
{
    return OrderItem::factory()->create([
        'order_id' => Order::factory()->create($order + ['payment_status' => PaymentStatus::Paid])->id,
        'product_type' => $product->getMorphClass(),
        'product_id' => $product->id,
    ]);
}

function fileEndpoint(OrderItem $item, string $fileId): string
{
    return "/store/customers/me/orders/{$item->order->public_id}/items/{$item->public_id}/files/{$fileId}";
}

it('stores the files of a product on the private disk', function (): void {
    expect($this->file->disk)->toBe('local');

    Storage::disk('local')->assertExists($this->file->getPathRelativeToRoot());
});

it('never exposes the url of a file in the public catalog', function (): void {
    $show = $this->getJson('/store/products/'.$this->product->slug)->assertOk();
    $list = $this->getJson('/store/products')->assertOk();

    expect($show->json('data.attributes.files'))->toBe([[
        'id' => $this->file->uuid,
        'name' => 'ebook',
        'extension' => 'txt',
        'size' => $this->file->size,
    ]])
        ->and($show->getContent())->not->toContain('ebook.txt')
        ->and($list->getContent())->not->toContain('ebook.txt');
});

it('lists the files of a paid order line on the account order', function (): void {
    $paid = orderedFile($this->product, ['customer_id' => $this->customer->id]);
    $pending = orderedFile($this->product, ['customer_id' => $this->customer->id, 'payment_status' => PaymentStatus::Pending]);

    Sanctum::actingAs($this->customer, ['store']);

    $line = fn (OrderItem $item): array => $this
        ->getJson("/store/customers/me/orders/{$item->order->public_id}?include=items")
        ->assertOk()
        ->json('included.0.attributes');

    expect($line($paid)['files'])->toBe([[
        'id' => $this->file->uuid,
        'name' => 'ebook',
        'extension' => 'txt',
        'size' => $this->file->size,
    ]])
        ->and($line($pending))->not->toHaveKey('files');
});

it('delivers a file through a temporary signed link to the customer who paid for it', function (): void {
    $item = orderedFile($this->product, ['customer_id' => $this->customer->id]);

    Sanctum::actingAs($this->customer, ['store']);

    $link = $this->getJson(fileEndpoint($item, $this->file->uuid))->assertOk()->json('data');

    expect($link['expires_at'])->not->toBeNull();

    $this->get($link['url'])
        ->assertOk()
        ->assertHeader('Content-Disposition', 'attachment; filename=ebook.txt');

    $this->get(strtok($link['url'], '?'))->assertForbidden();

    $this->travel(6)->minutes();

    $this->get($link['url'])->assertForbidden();
});

it('names the download after the file instead of its stored name', function (): void {
    $file = $this->product
        ->addMedia(UploadedFile::fake()->createWithContent('guide.txt', 'paid content'))
        ->usingName("Guide: été\n 100%")
        ->usingFileName('kR8fQ2xZ.txt')
        ->toMediaCollection('files');

    Sanctum::actingAs($this->customer, ['store']);

    $url = $this->getJson(fileEndpoint(orderedFile($this->product, ['customer_id' => $this->customer->id]), $file->uuid))
        ->assertOk()
        ->json('data.url');

    $this->get($url)->assertOk()->assertHeader(
        'Content-Disposition',
        "attachment; filename=\"Guide: ete 100.txt\"; filename*=utf-8''Guide%3A%20%C3%A9t%C3%A9%20100%25.txt",
    );
});

it('signs the link on the bucket itself when the files live on s3', function (): void {
    $this->freezeTime();

    Storage::fake('s3')->buildTemporaryUrlsUsing(
        fn (string $path, DateTimeInterface $expiration, array $options): string => 'https://bucket.test/'.$path.'?'.http_build_query($options + ['expires' => $expiration->getTimestamp()]),
    );
    config(['shopper.media.storage.files_disk' => 's3']);

    $product = Product::factory()->publish()->create(['type' => ProductType::Virtual]);
    $file = $product
        ->addMedia(UploadedFile::fake()->createWithContent('fonts.txt', 'paid content'))
        ->usingFileName('kR8fQ2xZ.txt')
        ->toMediaCollection('files');

    Sanctum::actingAs($this->customer, ['store']);

    $url = $this->getJson(fileEndpoint(orderedFile($product, ['customer_id' => $this->customer->id]), $file->uuid))
        ->assertOk()
        ->json('data.url');

    parse_str((string) parse_url($url, PHP_URL_QUERY), $query);

    expect($url)->toStartWith('https://bucket.test/'.$file->getPathRelativeToRoot())
        ->and($query['ResponseContentDisposition'])->toBe('attachment; filename=fonts.txt')
        ->and((int) $query['expires'])->toBe(now()->addMinutes(5)->getTimestamp());
});

it('delivers the files of a partially refunded order', function (): void {
    $item = orderedFile($this->product, ['customer_id' => $this->customer->id, 'payment_status' => PaymentStatus::PartiallyRefunded]);

    Sanctum::actingAs($this->customer, ['store']);

    $this->getJson(fileEndpoint($item, $this->file->uuid))->assertOk()->assertJsonStructure(['data' => ['url', 'expires_at']]);
});

it('streams a file from the disk it is stored on', function (): void {
    config(['shopper.media.storage.files_disk' => 'public']);

    $product = Product::factory()->publish()->create(['type' => ProductType::Virtual]);
    $file = $product
        ->addMedia(UploadedFile::fake()->createWithContent('template.txt', 'paid content'))
        ->toMediaCollection('files');

    config(['shopper.media.storage.files_disk' => 'local']);

    Sanctum::actingAs($this->customer, ['store']);

    $url = $this->getJson(fileEndpoint(orderedFile($product, ['customer_id' => $this->customer->id]), $file->uuid))
        ->assertOk()
        ->json('data.url');

    $this->get($url)->assertOk()->assertStreamedContent('paid content');
});

it('refuses a file requested through the line of another order', function (): void {
    $other = Product::factory()->publish()->create(['type' => ProductType::Virtual]);
    $other->addMedia(UploadedFile::fake()->createWithContent('course.txt', 'paid content'))->toMediaCollection('files');

    $paid = orderedFile($other, ['customer_id' => $this->customer->id]);
    $pending = orderedFile($this->product, ['customer_id' => $this->customer->id, 'payment_status' => PaymentStatus::Pending]);
    $foreign = orderedFile($this->product, ['customer_id' => User::factory()->create()->id]);

    Sanctum::actingAs($this->customer, ['store']);

    $through = fn (OrderItem $item): string => "/store/customers/me/orders/{$paid->order->public_id}/items/{$item->public_id}/files/{$this->file->uuid}";

    $this->getJson($through($pending))->assertNotFound();
    $this->getJson($through($foreign))->assertNotFound();
});

it('refuses a file while the order is not paid or once it is refunded', function (PaymentStatus $status): void {
    $item = orderedFile($this->product, ['customer_id' => $this->customer->id, 'payment_status' => $status]);

    Sanctum::actingAs($this->customer, ['store']);

    $this->getJson(fileEndpoint($item, $this->file->uuid))->assertForbidden();
})->with([PaymentStatus::Pending, PaymentStatus::Authorized, PaymentStatus::Refunded, PaymentStatus::Voided]);

it('keeps the files of an order away from guests, other customers and other products', function (): void {
    $item = orderedFile($this->product, ['customer_id' => $this->customer->id]);

    $other = Product::factory()->publish()->create(['type' => ProductType::Virtual]);
    $unpaid = $other
        ->addMedia(UploadedFile::fake()->createWithContent('course.txt', 'paid content'))
        ->toMediaCollection('files');

    $this->getJson(fileEndpoint($item, $this->file->uuid))->assertUnauthorized();

    Sanctum::actingAs($this->customer, ['store']);
    $this->getJson(fileEndpoint($item, $unpaid->uuid))->assertNotFound();

    Sanctum::actingAs(User::factory()->create(), ['store']);
    $this->getJson(fileEndpoint($item, $this->file->uuid))->assertNotFound();
});

it('moves the files uploaded on the public disk to the private one', function (): void {
    config(['shopper.media.storage.files_disk' => 'public']);

    $product = Product::factory()->publish()->create(['type' => ProductType::Virtual]);
    $exposed = $product
        ->addMedia(UploadedFile::fake()->createWithContent('template.txt', 'paid content'))
        ->toMediaCollection('files');

    config(['shopper.media.storage.files_disk' => 'local']);

    $this->artisan('shopper:files:secure')->assertSuccessful();

    $moved = $product->refresh()->getFirstMedia('files');

    expect($moved->disk)->toBe('local');

    Storage::disk('public')->assertMissing($exposed->getPathRelativeToRoot());
    Storage::disk('local')->assertExists($moved->getPathRelativeToRoot());
});
