@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto">
    <!-- Success Alert -->
    @if(session('success'))
    <div class="card mb-6" style="border-left: 4px solid var(--teal); background: #E8F5F7; margin-bottom: 24px;">
        <div style="color: #1A7A8A; font-weight: 600;">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
        </div>
    </div>
    @endif

    <!-- Add New Category Card -->
    <div class="card mb-8">
        <h2 class="text-2xl font-bold mb-6" style="color: var(--text-primary);">
            <i class="fas fa-plus-circle mr-2" style="color: var(--coral);"></i> Add New Category
        </h2>
        <form method="POST" action="{{ route('categories.store') }}">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label>Category Name <span style="color: var(--coral);">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="e.g., Electronics" required>
                    @error('name')
                    <p style="color: var(--text-secondary); font-size: 12px; margin-top: 6px;"><i class="fas fa-info-circle mr-1"></i> {{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label>Description</label>
                    <input type="text" name="description" value="{{ old('description') }}" placeholder="Brief description...">
                </div>
            </div>
            <div class="mt-6">
                <button type="submit" class="btn-primary">
                    <i class="fas fa-save mr-2"></i> Create Category
                </button>
            </div>
        </form>
    </div>

    <!-- Categories List -->
    <div class="card">
        <h3 class="text-2xl font-bold mb-6" style="color: var(--text-primary);">
            <i class="fas fa-list mr-2" style="color: var(--teal);"></i> All Categories
        </h3>
        
        @if($categories->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr style="border-bottom: 2px solid var(--light-gray);">
                        <th class="text-left px-6 py-4" style="color: var(--text-secondary); font-weight: 600; font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px;">ID</th>
                        <th class="text-left px-6 py-4" style="color: var(--text-secondary); font-weight: 600; font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px;">Name</th>
                        <th class="text-left px-6 py-4" style="color: var(--text-secondary); font-weight: 600; font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px;">Description</th>
                        <th class="text-left px-6 py-4" style="color: var(--text-secondary); font-weight: 600; font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px;">Products</th>
                        <th class="text-left px-6 py-4" style="color: var(--text-secondary); font-weight: 600; font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categories as $category)
                    <tr style="border-bottom: 1px solid var(--light-gray); transition: background 0.2s ease;" onmouseover="this.style.background='rgba(232, 153, 122, 0.05)'" onmouseout="this.style.background='transparent'">
                        <td class="px-6 py-4 text-sm" style="color: var(--text-secondary);">#{{ $category->id }}</td>
                        <td class="px-6 py-4 text-sm font-bold" style="color: var(--text-primary);">🏷️ {{ $category->name }}</td>
                        <td class="px-6 py-4 text-sm" style="color: var(--text-secondary);">{{ $category->description ?? '—' }}</td>
                        <td class="px-6 py-4">
                            <span style="display: inline-block; background: linear-gradient(135deg, rgba(232, 153, 122, 0.1), rgba(124, 185, 200, 0.1)); color: var(--text-primary); padding: 6px 12px; border-radius: 20px; font-size: 13px; font-weight: 600;">
                                📦 {{ $category->products_count }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex gap-3">
                                <a href="{{ route('categories.edit', $category) }}" class="text-sm font-bold" style="color: var(--teal); text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='var(--coral)'" onmouseout="this.style.color='var(--teal)'">
                                    <i class="fas fa-edit mr-1"></i> Edit
                                </a>
                                <button type="button" class="text-sm font-bold" style="color: var(--coral); background: none; border: none; cursor: pointer; text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='#D67A5F'" onmouseout="this.style.color='var(--coral)'" onclick="openDeleteModal('{{ $category->id }}', 'category', '{{ $category->name }}')">
                                    <i class="fas fa-trash mr-1"></i> Delete
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div style="text-align: center; padding: 60px 20px;">
            <div style="font-size: 48px; margin-bottom: 16px;">📭</div>
            <p style="color: var(--text-secondary); font-size: 16px; font-weight: 500;">No categories yet</p>
            <p style="color: var(--text-secondary); font-size: 13px; margin-top: 8px;">Create your first category to get started!</p>
        </div>
        @endif
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; justify-content: center; align-items: center;">
    <div class="card" style="max-width: 400px; width: 90%;">
        <h3 style="color: var(--coral); font-size: 20px; font-weight: bold; margin-bottom: 12px;">
            <i class="fas fa-exclamation-triangle mr-2"></i> Confirm Delete
        </h3>
        <p style="color: var(--text-secondary); margin-bottom: 20px;" id="deleteMessage"></p>
        <div style="display: flex; gap: 12px; justify-content: flex-end;">
            <button onclick="closeDeleteModal()" class="btn-secondary" style="background: var(--light-gray); color: var(--text-primary); padding: 10px 20px; border: none; border-radius: 6px; cursor: pointer; font-weight: 600;">
                Cancel
            </button>
            <form id="deleteForm" method="POST" style="display: inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-danger" style="background: var(--coral); color: white; padding: 10px 20px; border: none; border-radius: 6px; cursor: pointer; font-weight: 600;">
                    Delete
                </button>
            </form>
        </div>
    </div>
</div>

<script>
function openDeleteModal(id, type, name) {
    const modal = document.getElementById('deleteModal');
    const message = document.getElementById('deleteMessage');
    const form = document.getElementById('deleteForm');
    
    message.textContent = `Are you sure you want to delete "${name}"? This action cannot be undone.`;
    
    if (type === 'category') {
        form.action = '/categories/' + id;
    }
    
    modal.style.display = 'flex';
}

function closeDeleteModal() {
    const modal = document.getElementById('deleteModal');
    modal.style.display = 'none';
}

// Close modal when clicking outside
document.getElementById('deleteModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeleteModal();
    }
});
</script>
@endsection