@extends('layouts.admin')

@section('header', 'Recycle Bin - Batches')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div class="flex gap-3">
        <a href="{{ route('admin.batches.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-2 px-4 rounded-lg flex items-center gap-2 transition-colors border border-gray-200">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Back to Stock
        </a>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Batch No</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Medicine</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deleted At</th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($batches as $b)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap font-mono text-sm text-gray-600">
                        {{ $b->batch_no }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $b->medicine ? $b->medicine->name : 'Unknown Medicine' }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $b->deleted_at->format('Y-m-d H:i') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <form action="{{ route('admin.batches.restore', $b->id) }}" method="POST" class="inline-block">
                            @csrf
                            <button type="submit" class="text-green-600 hover:text-green-900 mr-3">Restore</button>
                        </form>
                        <form action="{{ route('admin.batches.force-delete', $b->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Permanently delete? This cannot be undone.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900">Delete Permanently</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">
                        No items in recycle bin.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($batches->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $batches->links() }}
        </div>
    @endif
</div>
@endsection
