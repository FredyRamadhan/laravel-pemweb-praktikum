<?php

use App\Models\Catatan;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('can view home page with notes', function () {
    $note1 = Catatan::create([
        'judul' => 'Test Note 1',
        'isi' => 'This is the content of test note 1'
    ]);

    $note2 = Catatan::create([
        'judul' => 'Test Note 2',
        'isi' => 'This is the content of test note 2'
    ]);

    $response = $this->get('/');

    $response->assertStatus(200);
    $response->assertSee('Test Note 1');
    $response->assertSee('Test Note 2');
    $response->assertSee('This is the content of test note 1');
    $response->assertSee('This is the content of test note 2');
});

test('can view home page when no notes exist', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
    $response->assertSee('Belum ada catatan');
});

test('can create a new note', function () {
    $noteData = [
        'judul' => 'New Test Note',
        'isi' => 'This is a new test note content'
    ];

    $response = $this->post('/create', $noteData);

    $response->assertRedirect('/');
    $response->assertSessionHas('success', 'Catatan berhasil dibuat!');
    
    $this->assertDatabaseHas('catatans', $noteData);
});

test('cannot create note without title', function () {
    $noteData = [
        'isi' => 'This note has no title'
    ];

    $response = $this->post('/create', $noteData);

    $response->assertSessionHasErrors(['judul']);
    $this->assertDatabaseMissing('catatans', $noteData);
});

test('can create note with empty content', function () {
    $noteData = [
        'judul' => 'Note with no content',
        'isi' => ''
    ];

    $response = $this->post('/create', $noteData);

    $response->assertRedirect('/');
    $response->assertSessionHas('success', 'Catatan berhasil dibuat!');
    
    $this->assertDatabaseHas('catatans', [
        'judul' => 'Note with no content',
        'isi' => null
    ]);
});

test('can view edit page for existing note', function () {
    $note = Catatan::create([
        'judul' => 'Test Note',
        'isi' => 'Test content'
    ]);

    $response = $this->get("/edit/{$note->id}");

    $response->assertStatus(200);
    $response->assertSee('Edit Catatan');
    $response->assertSee($note->judul);
    $response->assertSee($note->isi);
});

test('can update existing note', function () {
    $note = Catatan::create([
        'judul' => 'Original Title',
        'isi' => 'Original content'
    ]);

    $updatedData = [
        'judul' => 'Updated Title',
        'isi' => 'Updated content'
    ];

    $response = $this->post("/update/{$note->id}", $updatedData);

    $response->assertRedirect('/');
    $response->assertSessionHas('success', 'Catatan berhasil diperbarui!');
    
    $this->assertDatabaseHas('catatans', array_merge(['id' => $note->id], $updatedData));
    $this->assertDatabaseMissing('catatans', [
        'id' => $note->id,
        'judul' => 'Original Title',
        'isi' => 'Original content'
    ]);
});

test('cannot update note without title', function () {
    $note = Catatan::create([
        'judul' => 'Original Title',
        'isi' => 'Original content'
    ]);

    $invalidData = [
        'isi' => 'Updated content without title'
    ];

    $response = $this->post("/update/{$note->id}", $invalidData);

    $response->assertSessionHasErrors(['judul']);

    $this->assertDatabaseHas('catatans', [
        'id' => $note->id,
        'judul' => 'Original Title',
        'isi' => 'Original content'
    ]);
});

test('can delete existing note', function () {
    $note = Catatan::create([
        'judul' => 'Note to Delete',
        'isi' => 'This note will be deleted'
    ]);

    $response = $this->post("/delete/{$note->id}");

    $response->assertRedirect('/');
    $response->assertSessionHas('success', 'Catatan berhasil dihapus!');
    
    $this->assertDatabaseMissing('catatans', [
        'id' => $note->id
    ]);
});

test('returns 404 when trying to edit non-existent note', function () {
    $response = $this->get('/edit/999');

    $response->assertStatus(404);
});

test('returns 404 when trying to update non-existent note', function () {
    $response = $this->post('/update/999', [
        'judul' => 'Updated Title',
        'isi' => 'Updated content'
    ]);

    $response->assertStatus(404);
});

test('returns 404 when trying to delete non-existent note', function () {
    $response = $this->post('/delete/999');

    $response->assertStatus(404);
});

test('title validation enforces maximum length', function () {
    $longTitle = str_repeat('a', 256);
    
    $noteData = [
        'judul' => $longTitle,
        'isi' => 'Content'
    ];

    $response = $this->post('/create', $noteData);

    $response->assertSessionHasErrors(['judul']);
    $this->assertDatabaseMissing('catatans', $noteData);
});

test('can handle special characters in note content', function () {
    $noteData = [
        'judul' => 'Special Characters Test',
        'isi' => 'Content with special chars: áéíóú ñ ¿¡ @#$%^&*()_+-=[]{}|;:,.<>?'
    ];

    $response = $this->post('/create', $noteData);

    $response->assertRedirect('/');
    $response->assertSessionHas('success', 'Catatan berhasil dibuat!');
    
    $this->assertDatabaseHas('catatans', $noteData);
});
