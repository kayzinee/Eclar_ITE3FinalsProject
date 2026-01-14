@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Success Alert -->
    @if(session('success'))
    <div class="card mb-6" style="border-left: 4px solid var(--teal); background: #E8F5F7;">
        <div style="color: #1A7A8A; font-weight: 600;">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
        </div>
    </div>
    @endif

    <!-- Add Product Form -->
    <div class="card mb-8">
        <h2 class="text-2xl font-bold mb-6" style="color: var(--text-primary);">
            <i class="fas fa-plus-circle mr-2" style="color: var(--coral);"></i> Add New Product
        </h2>
        <form method="POST" action="{{ route('products.store') }}">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div>
                    <label>Product Name <span style="color: var(--coral);">*</span></label>
                    <input type="text" name="name" placeholder="e.g., Wireless Mouse" required>
                </div>
                <div>
                    <label>Price <span style="color: var(--coral);">*</span></label>
                    <input type="number" step="0.01" name="price" placeholder="0.00" required>
                </div>
                <div>
                    <label>Stock <span style="color: var(--coral);">*</span></label>
                    <input type="number" name="stock" placeholder="0" required>
                </div>
                <div>
                    <label>Category</label>
                    <select name="category_id">
                        <option value="">Select a category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="mt-6">
                <button type="submit" class="btn-primary">
                    <i class="fas fa-save mr-2"></i> Create Product
                </button>
            </div>
        </form>
    </div>

    <!-- Products Table -->
    <div class="card">
        <h3 class="text-2xl font-bold mb-6" style="color: var(--text-primary);">
            <i class="fas fa-list mr-2" style="color: var(--teal);"></i> All Products
        </h3>
        
        @if($products->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr style="border-bottom: 2px solid var(--light-gray);">
                        <th class="text-left px-6 py-4" style="color: var(--text-secondary); font-weight: 600; font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px;">Product</th>
                        <th class="text-left px-6 py-4" style="color: var(--text-secondary); font-weight: 600; font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px;">Category</th>
                        <th class="text-left px-6 py-4" style="color: var(--text-secondary); font-weight: 600; font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px;">Price</th>
                        <th class="text-left px-6 py-4" style="color: var(--text-secondary); font-weight: 600; font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px;">Stock</th>
                        <th class="text-left px-6 py-4" style="color: var(--text-secondary); font-weight: 600; font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px;">Status</th>
                        <th class="text-left px-6 py-4" style="color: var(--text-secondary); font-weight: 600; font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $product)
                    <tr style="border-bottom: 1px solid var(--light-gray); transition: background 0.2s ease;" onmouseover="this.style.background='rgba(232, 153, 122, 0.05)'" onmouseout="this.style.background='transparent'">
                        <td class="px-6 py-4 text-sm font-bold" style="color: var(--text-primary);">📦 {{ $product->name }}</td>
                        <td class="px-6 py-4 text-sm" style="color: var(--text-secondary);">{{ $product->category?->name ?? '—' }}</td>
                        <td class="px-6 py-4 text-sm font-bold" style="color: var(--coral);">${{ number_format($product->price, 2) }}</td>
                        <td class="px-6 py-4 text-sm font-bold" style="color: var(--text-primary);">{{ $product->stock }}</td>
                        <td class="px-6 py-4">
                            @if($product->stock < 5)
                                <span style="display: inline-block; background: rgba(232, 153, 122, 0.15); color: #D67A5F; padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">
                                    ⚠️ Low Stock
                                </span>
                            @else
                                <span style="display: inline-block; background: rgba(124, 185, 200, 0.15); color: #1A7A8A; padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">
                                    ✓ In Stock
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex gap-3">
                                <a href="{{ route('products.edit', $product) }}" class="text-sm font-bold" style="color: var(--teal); text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='var(--coral)'" onmouseout="this.style.color='var(--teal)'">
                                    <i class="fas fa-edit mr-1"></i> Edit
                                </a>
                                <button type="button" class="text-sm font-bold" style="color: var(--coral); background: none; border: none; cursor: pointer; text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='#D67A5F'" onmouseout="this.style.color='var(--coral)'" onclick="openDeleteModal('{{ $product->id }}', 'product', '{{ $product->name }}')">
                                    <i class="fas fa-trash mr-1"></i> Delete
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6 border-t pt-6" style="border-color: var(--light-gray);">
            {{ $products->links() }}
        </div>
        @else
        <div style="text-align: center; padding: 60px 20px;">
            <div style="font-size: 48px; margin-bottom: 16px;">📭</div>
            <p style="color: var(--text-secondary); font-size: 16px; font-weight: 500;">No products yet</p>
            <p style="color: var(--text-secondary); font-size: 13px; margin-top: 8px;">Create your first product to get started!</p>
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
    
    if (type === 'product') {
        form.action = '/products/' + id;
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