<div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm p-6 flex flex-col justify-between">
    <div>
        <h3 class="text-xl font-bold mb-2 text-gray-900 dark:text-gray-100">{{ $title }}</h3>
        <p class="text-gray-600 dark:text-gray-300 text-base">{{ $slot }}</p>
    </div>
    <div class="flex items-center justify-end space-x-2 mt-6">
        <a href="/edit/{{ $id }}" class="py-2 px-4 text-sm font-medium text-gray-500 hover:text-gray-800 dark:text-gray-400 dark:hover:text-white transition-colors">Ubah</a>
        <form action="/delete/{{ $id }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus catatan ini?')">
            @csrf
            <button type="submit" class="py-2 px-4 text-sm font-medium text-red-500 hover:text-white hover:bg-red-600 rounded-md transition-colors">Hapus</button>
        </form>
    </div>
</div>



