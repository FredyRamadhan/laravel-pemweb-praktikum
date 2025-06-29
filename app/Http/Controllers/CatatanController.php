<?php

namespace App\Http\Controllers;

use App\Models\Catatan;
use Illuminate\Http\Request;

class CatatanController extends Controller
{
    public function create(Request $request){
        $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'nullable|string'
        ]);

        Catatan::create([
            'judul' => $request->judul,
            'isi' => $request->isi
        ]);

        return redirect('/')->with('success', 'Catatan berhasil dibuat!');
    }

    public function read(){
        $notes = Catatan::all();
        return view('Home', compact('notes'));
    }

    public function update(Request $request, $id){
        $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'nullable|string'
        ]);

        $note = Catatan::findOrFail($id);
        $note->update([
            'judul' => $request->judul,
            'isi' => $request->isi
        ]);

        return redirect('/')->with('success', 'Catatan berhasil diperbarui!');
    }

    public function delete($id){
        $note = Catatan::findOrFail($id);
        $note->delete();

        return redirect('/')->with('success', 'Catatan berhasil dihapus!');
    }

    public function edit($id){
        $note = Catatan::findOrFail($id);
        $notes = Catatan::all();
        return view('Home', compact('notes', 'note'));
    }
}
