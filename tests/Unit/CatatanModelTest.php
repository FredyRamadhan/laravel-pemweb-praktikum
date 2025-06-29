<?php

use App\Models\Catatan;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('can create catatan with fillable attributes', function () {
    $data = [
        'judul' => 'Test Title',
        'isi' => 'Test content'
    ];

    $catatan = Catatan::create($data);

    expect($catatan)->toBeInstanceOf(Catatan::class);
    expect($catatan->judul)->toBe('Test Title');
    expect($catatan->isi)->toBe('Test content');
    expect($catatan->id)->toBeInt();
    expect($catatan->created_at)->not->toBeNull();
    expect($catatan->updated_at)->not->toBeNull();
});

test('can create catatan with null isi', function () {
    $data = [
        'judul' => 'Test Title',
        'isi' => null
    ];

    $catatan = Catatan::create($data);

    expect($catatan->judul)->toBe('Test Title');
    expect($catatan->isi)->toBeNull();
});

test('fillable attributes are correctly defined', function () {
    $catatan = new Catatan();
    $fillable = $catatan->getFillable();

    expect($fillable)->toContain('judul');
    expect($fillable)->toContain('isi');
    expect($fillable)->toHaveCount(2);
});

test('can update catatan attributes', function () {
    $catatan = Catatan::create([
        'judul' => 'Original Title',
        'isi' => 'Original content'
    ]);

    $catatan->update([
        'judul' => 'Updated Title',
        'isi' => 'Updated content'
    ]);

    expect($catatan->fresh()->judul)->toBe('Updated Title');
    expect($catatan->fresh()->isi)->toBe('Updated content');
});

test('can delete catatan', function () {
    $catatan = Catatan::create([
        'judul' => 'Test Title',
        'isi' => 'Test content'
    ]);

    $id = $catatan->id;
    $catatan->delete();

    expect(Catatan::find($id))->toBeNull();
});

test('timestamps are automatically managed', function () {
    $catatan = Catatan::create([
        'judul' => 'Test Title',
        'isi' => 'Test content'
    ]);

    expect($catatan->created_at)->not->toBeNull();
    expect($catatan->updated_at)->not->toBeNull();
    expect($catatan->created_at)->toEqual($catatan->updated_at);

    sleep(1);
    $catatan->update(['judul' => 'Updated Title']);

    expect($catatan->fresh()->updated_at)->toBeGreaterThan($catatan->created_at);
});

test('can retrieve all catatans', function () {
    Catatan::create(['judul' => 'Note 1', 'isi' => 'Content 1']);
    Catatan::create(['judul' => 'Note 2', 'isi' => 'Content 2']);
    Catatan::create(['judul' => 'Note 3', 'isi' => 'Content 3']);

    $catatans = Catatan::all();

    expect($catatans)->toHaveCount(3);
    expect($catatans->pluck('judul')->toArray())->toContain('Note 1', 'Note 2', 'Note 3');
});

test('can find catatan by id', function () {
    $catatan = Catatan::create([
        'judul' => 'Findable Note',
        'isi' => 'This note can be found'
    ]);

    $found = Catatan::find($catatan->id);

    expect($found)->not->toBeNull();
    expect($found->judul)->toBe('Findable Note');
    expect($found->isi)->toBe('This note can be found');
});

test('returns null when finding non-existent catatan', function () {
    $found = Catatan::find(999);

    expect($found)->toBeNull();
});
