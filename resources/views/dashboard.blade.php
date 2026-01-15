@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Success Alert -->
    @if(session('success'))
    <div class="card mb-6" style="border-left: 4px solid #7CB9C8; background: #E8F5F7;">
        <div style="color: #1A7A8A; font-weight: 600;">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
        </div>
    </div>
    @endif

    <!-- Stats Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Products -->
        <div class="card group hover:shadow-lg transition-all cursor-default" style="border-left: 4px solid var(--coral);">
            <div class="flex items-start justify-between">
                <div>
                    <p style="color: var(--text-secondary); font-size: 13px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">
                         Total Products
                    </p>
                    <p class="text-4xl font-bold mt-3" style="color: var(--coral);">{{ $totalProducts }}</p>
                </div>
            </div>
            <p style="color: var(--text-secondary); font-size: 12px; margin-top: 12px;">Active inventory items</p>
        </div>

        <!-- Total Categories -->
        <div class="card group hover:shadow-lg transition-all cursor-default" style="border-left: 4px solid var(--teal);">
            <div class="flex items-start justify-between">
                <div>
                    <p style="color: var(--text-secondary); font-size: 13px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">
                        Categories
                    </p>
                    <p class="text-4xl font-bold mt-3" style="color: var(--teal);">{{ $totalCategories }}</p>
                </div>
            </div>
            <p style="color: var(--text-secondary); font-size: 12px; margin-top: 12px;">Organized by type</p>
        </div>

        <!-- Low Stock Items -->
        <div class="card group hover:shadow-lg transition-all cursor-default" style="border-left: 4px solid #E8997A;">
            <div class="flex items-start justify-between">
                <div>
                    <p style="color: var(--text-secondary); font-size: 13px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">
                        Low Stock
                    </p>
                    <p class="text-4xl font-bold mt-3" style="color: #E8997A;">{{ $lowStock }}</p>
                </div>
            </div>
            <p style="color: var(--text-secondary); font-size: 12px; margin-top: 12px;">Need attention soon</p>
        </div>

        <!-- Reorder Rate -->
        <div class="card group hover:shadow-lg transition-all cursor-default" style="border-left: 4px solid #7CB9C8;">
            <div class="flex items-start justify-between">
                <div>
                    <p style="color: var(--text-secondary); font-size: 13px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">
                        Reorder Rate
                    </p>
                    <p class="text-4xl font-bold mt-3" style="color: #7CB9C8;">{{ $reorderRate }}<span style="font-size: 24px;">%</span></p>
                </div>
            </div>
            <p style="color: var(--text-secondary); font-size: 12px; margin-top: 12px;">Of total inventory</p>
        </div>
    </div>

    <!-- Category Overview & Products -->
    <div class="card mb-8">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
            <h2 class="text-2xl font-bold" style="color: var(--text-primary);">Category Overview</h2>
        </div>

        <!-- Search Bar -->
        <div style="margin-bottom: 24px;">
            <div style="position: relative;">
                <i class="fas fa-search" style="position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: var(--text-secondary);"></i>
                <input 
                    type="text" 
                    id="categorySearch" 
                    placeholder="Search categories or products..." 
                    style="width: 100%; padding-left: 44px; background: var(--light-gray);"
                />
            </div>
        </div>

        <!-- Category Table -->
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="border-bottom: 2px solid var(--light-gray);">
                        <th style="padding: 16px; text-align: left; color: var(--text-secondary); font-weight: 600; font-size: 13px; text-transform: uppercase;">Category Name</th>
                        <th style="padding: 16px; text-align: left; color: var(--text-secondary); font-weight: 600; font-size: 13px; text-transform: uppercase;">Products in Category</th>
                        <th style="padding: 16px; text-align: center; color: var(--text-secondary); font-weight: 600; font-size: 13px; text-transform: uppercase;">Total Products</th>
                        <th style="padding: 16px; text-align: center; color: var(--text-secondary); font-weight: 600; font-size: 13px; text-transform: uppercase;">Status</th>
                        <th style="padding: 16px; text-align: center; color: var(--text-secondary); font-weight: 600; font-size: 13px; text-transform: uppercase;">Actions</th>
                    </tr>
                </thead>
                <tbody id="categoryTable">
                    @forelse($categories as $category)
                    <tr class="category-row" data-category="{{ strtolower($category->name) }}" data-products="{{ strtolower($category->products->pluck('name')->join(' ')) }}" style="border-bottom: 1px solid var(--light-gray); transition: all 0.2s ease; cursor: pointer;" onclick="toggleProductList(this, event)">
                        <td style="padding: 16px; color: var(--text-primary); font-weight: 600;">
                            <i class="fas fa-chevron-right" style="margin-right: 8px; transition: transform 0.2s ease; display: inline-block;" class="toggle-icon"></i>
                            {{ $category->name }}
                        </td>
                        <td style="padding: 16px; color: var(--text-primary);">
                            <span class="product-count">{{ $category->products_count }} item{{ $category->products_count != 1 ? 's' : '' }}</span>
                        </td>
                        <td style="padding: 16px; text-align: center; color: var(--text-primary); font-weight: 600;">{{ $category->products_count }}</td>
                        <td style="padding: 16px; text-align: center;">
                            @php
                                $lowStockInCategory = $category->products->where($qtyCol, '<', 5)->count();
                            @endphp
                            @if($lowStockInCategory > 0)
                                <span style="background: #FFF3CD; color: #856404; padding: 6px 12px; border-radius: 8px; font-size: 12px; font-weight: 600; display: inline-block;">Low Stock</span>
                            @else
                                <span style="background: #D4EDDA; color: #155724; padding: 6px 12px; border-radius: 8px; font-size: 12px; font-weight: 600; display: inline-block;">Active</span>
                            @endif
                        </td>
                        <td style="padding: 16px; text-align: center;">
                            <a href="{{ route('categories.edit', $category->id) }}" onclick="event.stopPropagation()" style="color: var(--teal); text-decoration: none; font-weight: 600; transition: color 0.2s ease;" onmouseover="this.style.color='var(--coral)'" onmouseout="this.style.color='var(--teal)'">View Details</a>
                        </td>
                    </tr>
                    <!-- Products Dropdown Row -->
                    <tr class="products-dropdown" style="display: none; background: #FAFAF8;">
                        <td colspan="5" style="padding: 0;">
                            <div style="padding: 16px 16px 16px 64px; border-top: 1px solid var(--light-gray);">
                                <p style="color: var(--text-secondary); font-size: 12px; text-transform: uppercase; font-weight: 600; margin-bottom: 12px;">Products in Category</p>
                                @if($category->products_count > 0)
                                    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 12px;">
                                        @foreach($category->products as $product)
                                        <div style="background: white; padding: 12px; border-radius: 8px; border: 1px solid var(--light-gray); transition: all 0.2s ease;" onmouseover="this.style.boxShadow='0 2px 8px rgba(0,0,0,0.08)'" onmouseout="this.style.boxShadow='none'">
                                            <p style="color: var(--text-primary); font-weight: 600; font-size: 13px; margin-bottom: 6px;">{{ $product->name }}</p>
                                            <p style="color: var(--text-secondary); font-size: 12px;">
                                                <i class="fas fa-cube" style="margin-right: 4px;"></i>
                                                Stock: <strong>{{ $product->$qtyCol }}</strong>
                                            </p>
                                        </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p style="color: var(--text-secondary); font-style: italic;">No products</p>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="padding: 32px; text-align: center; color: var(--text-secondary);">
                            <i class="fas fa-inbox" style="font-size: 32px; margin-bottom: 12px; display: block; opacity: 0.5;"></i>
                            No categories found
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- No Results Message -->
        <div id="noResults" style="display: none; text-align: center; padding: 32px; color: var(--text-secondary);">
            <i class="fas fa-search" style="font-size: 32px; margin-bottom: 12px; display: block; opacity: 0.5;"></i>
            <p>No categories or products found matching your search</p>
        </div>

        <script>
            function toggleProductList(row, event) {
                // Don't toggle if clicking on the View Details link
                if (event.target.closest('a')) {
                    return;
                }

                const nextRow = row.nextElementSibling;
                const icon = row.querySelector('.toggle-icon');
                
                if (nextRow && nextRow.classList.contains('products-dropdown')) {
                    if (nextRow.style.display === 'none' || nextRow.style.display === '') {
                        nextRow.style.display = 'table-row';
                        icon.style.transform = 'rotate(90deg)';
                    } else {
                        nextRow.style.display = 'none';
                        icon.style.transform = 'rotate(0deg)';
                    }
                }
            }

            const searchInput = document.getElementById('categorySearch');
            const categoryRows = document.querySelectorAll('.category-row');
            const noResults = document.getElementById('noResults');
            const categoryTable = document.getElementById('categoryTable');

            searchInput.addEventListener('keyup', function() {
                const searchTerm = this.value.toLowerCase();
                let visibleRows = 0;

                categoryRows.forEach(row => {
                    const categoryName = row.getAttribute('data-category');
                    const productNames = row.getAttribute('data-products');
                    const nextRow = row.nextElementSibling;
                    
                    if (categoryName.includes(searchTerm) || productNames.includes(searchTerm)) {
                        row.style.display = '';
                        // Hide dropdown when searching
                        if (nextRow && nextRow.classList.contains('products-dropdown')) {
                            nextRow.style.display = 'none';
                            row.querySelector('.toggle-icon').style.transform = 'rotate(0deg)';
                        }
                        visibleRows++;
                    } else {
                        row.style.display = 'none';
                        // Also hide the corresponding dropdown
                        if (nextRow && nextRow.classList.contains('products-dropdown')) {
                            nextRow.style.display = 'none';
                        }
                    }
                });

                // Show/hide no results message
                if (visibleRows === 0) {
                    categoryTable.style.display = 'none';
                    noResults.style.display = 'block';
                } else {
                    categoryTable.style.display = '';
                    noResults.style.display = 'none';
                }
            });
        </script>
    </div>

    <!-- Products Overview with Filters & Pagination -->
    <div class="card mb-8">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
            <h2 class="text-2xl font-bold" style="color: var(--text-primary);">Products</h2>
        </div>

        <!-- Filters Section -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 16px; margin-bottom: 24px;">
            <div>
                <label style="display: block; color: var(--text-primary); font-weight: 600; margin-bottom: 8px;">Search Product</label>
                <div style="position: relative;">
                    <i class="fas fa-search" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--text-secondary);"></i>
                    <input 
                        type="text" 
                        id="productSearch" 
                        placeholder="Enter product name..." 
                        style="width: 100%; padding-left: 36px; background: var(--light-gray);"
                    />
                </div>
            </div>
            <div>
                <label style="display: block; color: var(--text-primary); font-weight: 600; margin-bottom: 8px;">Filter by Category</label>
                <select id="categoryFilter" style="width: 100%; background: var(--light-gray);">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->name }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <!-- Export Button -->
        <div style="display: flex; justify-content: flex-end; margin-bottom: 16px;">
            <form method="GET" action="{{ route('dashboard.export.pdf') }}" id="exportForm">
                <input type="hidden" name="search" id="exportSearch">
                <input type="hidden" name="category" id="exportCategory">

                <button type="submit"
                    style="background: var(--teal); color: white; padding: 10px 16px; border-radius: 6px; font-weight: 600; transition: all 0.2s ease;"
                    onmouseover="this.style.background='var(--coral)'"
                    onmouseout="this.style.background='var(--teal)'">
                    <i class="fas fa-file-export mr-2"></i> Export Filtered Products
                </button>
            </form>
        </div>

        <!-- Products Table -->
        <div style="overflow-x: auto; margin-bottom: 20px;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="border-bottom: 2px solid var(--light-gray);">
                        <th style="padding: 16px; text-align: left; color: var(--text-secondary); font-weight: 600; font-size: 13px; text-transform: uppercase;">Product</th>
                        <th style="padding: 16px; text-align: left; color: var(--text-secondary); font-weight: 600; font-size: 13px; text-transform: uppercase;">Category</th>
                        <th style="padding: 16px; text-align: center; color: var(--text-secondary); font-weight: 600; font-size: 13px; text-transform: uppercase;">Price</th>
                        <th style="padding: 16px; text-align: center; color: var(--text-secondary); font-weight: 600; font-size: 13px; text-transform: uppercase;">Stock</th>
                        <th style="padding: 16px; text-align: center; color: var(--text-secondary); font-weight: 600; font-size: 13px; text-transform: uppercase;">Status</th>
                    </tr>
                </thead>
                <tbody id="productsTable">
                    @forelse($allProducts ?? [] as $product)
                    <tr class="product-row" data-product="{{ strtolower($product->name) }}" data-category="{{ strtolower($product->category?->name ?? '') }}" style="border-bottom: 1px solid var(--light-gray); transition: all 0.2s ease;">
                        <td style="padding: 16px; color: var(--text-primary); font-weight: 600;">📦 {{ $product->name }}</td>
                        <td style="padding: 16px; color: var(--text-secondary);">{{ $product->category?->name ?? '—' }}</td>
                        <td style="padding: 16px; text-align: center; color: var(--coral); font-weight: 600;">${{ number_format($product->price, 2) }}</td>
                        <td style="padding: 16px; text-align: center; color: var(--text-primary); font-weight: 600;">{{ $product->stock }}</td>
                        <td style="padding: 16px; text-align: center;">
                            @if($product->stock < 5)
                                <span style="background: rgba(232, 153, 122, 0.15); color: #D67A5F; padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; display: inline-block;">⚠️ Low Stock</span>
                            @else
                                <span style="background: rgba(124, 185, 200, 0.15); color: #1A7A8A; padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; display: inline-block;">✓ In Stock</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="padding: 32px; text-align: center; color: var(--text-secondary);">
                            <i class="fas fa-inbox" style="font-size: 32px; margin-bottom: 12px; display: block; opacity: 0.5;"></i>
                            No products found
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div style="display: flex; justify-content: space-between; align-items: center; padding-top: 20px; border-top: 1px solid var(--light-gray);">
            <div style="color: var(--text-secondary); font-size: 13px;">
                <span id="itemsInfo">Showing 1-10 of {{ count($allProducts ?? []) }} products</span>
            </div>
            <div style="display: flex; gap: 8px; align-items: center;">
                <button id="prevBtn" onclick="previousPage()" class="btn-secondary" style="background: var(--light-gray); color: var(--text-primary); padding: 8px 12px; border: none; border-radius: 4px; cursor: pointer; font-weight: 600; transition: all 0.2s ease;" onmouseover="this.style.background='var(--text-secondary)'; this.style.color='white'" onmouseout="this.style.background='var(--light-gray)'; this.style.color='var(--text-primary)'">
                    <i class="fas fa-chevron-left mr-2"></i> Previous
                </button>
                <span id="pageInfo" style="color: var(--text-primary); font-weight: 600; padding: 0 12px;">Page 1</span>
                <button id="nextBtn" onclick="nextPage()" class="btn-primary" style="background: var(--teal); color: white; padding: 8px 12px; border: none; border-radius: 4px; cursor: pointer; font-weight: 600; transition: all 0.2s ease;" onmouseover="this.style.background='var(--coral)'" onmouseout="this.style.background='var(--teal)'">
                    Next <i class="fas fa-chevron-right ml-2"></i>
                </button>
            </div>
        </div>

        <!-- No Results Message -->
        <div id="noProductsResults" style="display: none; text-align: center; padding: 32px; color: var(--text-secondary);">
            <i class="fas fa-search" style="font-size: 32px; margin-bottom: 12px; display: block; opacity: 0.5;"></i>
            <p>No products found matching your filters</p>
        </div>

        <script>
            let currentPage = 1;
            const itemsPerPage = 10;
            let filteredProducts = [];
            const allProductRows = Array.from(document.querySelectorAll('.product-row'));

            function filterProducts() {
                const searchTerm = document.getElementById('productSearch').value.toLowerCase();
                const categoryTerm = document.getElementById('categoryFilter').value.toLowerCase();
                
                filteredProducts = allProductRows.filter(row => {
                    const productName = row.getAttribute('data-product');
                    const category = row.getAttribute('data-category');
                    
                    const matchesSearch = productName.includes(searchTerm);
                    const matchesCategory = categoryTerm === '' || category === categoryTerm;
                    
                    return matchesSearch && matchesCategory;
                });

                currentPage = 1;
                displayPage();
            }

            function syncExportFilters() {
                document.getElementById('exportSearch').value =
                    document.getElementById('productSearch').value;

                document.getElementById('exportCategory').value =
                    document.getElementById('categoryFilter').value;
            }

                // Sync whenever filters change
                document.getElementById('productSearch').addEventListener('keyup', syncExportFilters);
                document.getElementById('categoryFilter').addEventListener('change', syncExportFilters);

                // Initial sync
                syncExportFilters();


            function displayPage() {
                const totalPages = Math.ceil(filteredProducts.length / itemsPerPage);
                const startIdx = (currentPage - 1) * itemsPerPage;
                const endIdx = startIdx + itemsPerPage;

                // Hide all products
                allProductRows.forEach(row => row.style.display = 'none');

                // Show only products for current page
                filteredProducts.slice(startIdx, endIdx).forEach(row => {
                    row.style.display = '';
                });

                // Update info
                const itemStart = filteredProducts.length === 0 ? 0 : startIdx + 1;
                const itemEnd = Math.min(endIdx, filteredProducts.length);
                document.getElementById('itemsInfo').textContent = `Showing ${itemStart}-${itemEnd} of ${filteredProducts.length} products`;
                document.getElementById('pageInfo').textContent = `Page ${currentPage} of ${totalPages || 1}`;

                // Update button states
                document.getElementById('prevBtn').disabled = currentPage === 1;
                document.getElementById('prevBtn').style.opacity = currentPage === 1 ? '0.5' : '1';
                document.getElementById('prevBtn').style.cursor = currentPage === 1 ? 'not-allowed' : 'pointer';

                document.getElementById('nextBtn').disabled = currentPage >= totalPages;
                document.getElementById('nextBtn').style.opacity = currentPage >= totalPages ? '0.5' : '1';
                document.getElementById('nextBtn').style.cursor = currentPage >= totalPages ? 'not-allowed' : 'pointer';

                // Show/hide no results message
                const productsTable = document.getElementById('productsTable');
                const noResults = document.getElementById('noProductsResults');
                
                if (filteredProducts.length === 0) {
                    productsTable.style.display = 'none';
                    noResults.style.display = 'block';
                    document.getElementById('prevBtn').style.display = 'none';
                    document.getElementById('nextBtn').style.display = 'none';
                    document.getElementById('pageInfo').style.display = 'none';
                    document.getElementById('itemsInfo').style.display = 'none';
                } else {
                    productsTable.style.display = '';
                    noResults.style.display = 'none';
                    document.getElementById('prevBtn').style.display = '';
                    document.getElementById('nextBtn').style.display = '';
                    document.getElementById('pageInfo').style.display = '';
                    document.getElementById('itemsInfo').style.display = '';
                }
            }

            function nextPage() {
                if (currentPage < Math.ceil(filteredProducts.length / itemsPerPage)) {
                    currentPage++;
                    displayPage();
                }
            }

            function previousPage() {
                if (currentPage > 1) {
                    currentPage--;
                    displayPage();
                }
            }

            // Event listeners for filters
            document.getElementById('productSearch').addEventListener('keyup', filterProducts);
            document.getElementById('categoryFilter').addEventListener('change', filterProducts);

            // Initialize
            filterProducts();
        </script>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="card">
            <h3 class="text-lg font-bold mb-4">Quick Actions</h3>
            <div class="space-y-3">
                <a href="{{ route('products.index') }}" class="btn-primary w-full text-center block">
                    <i class="fas fa-plus mr-2"></i> Add New Product
                </a>
                <a href="{{ route('categories.index') }}" class="btn-secondary w-full text-center block">
                    <i class="fas fa-plus mr-2"></i> Add New Category
                </a>
            </div>
        </div>

        <div class="card">
            <h3 class="text-lg font-bold mb-4">Inventory Health</h3>
            <div style="background: var(--light-gray); border-radius: 12px; padding: 16px;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 12px;">
                    <span style="color: var(--text-secondary); font-weight: 600;">In Stock</span>
                    <span style="color: var(--coral); font-weight: 700;">{{ $totalProducts - $lowStock }}</span>
                </div>
                <div style="height: 8px; background: var(--light-gray); border-radius: 4px; overflow: hidden;">
                    <?php $percentage = $totalProducts ? round(((($totalProducts - $lowStock) / $totalProducts) * 100)) : 0; ?>
                    <div class="progress-bar" data-width="{{ $percentage }}" style="height: 100%; background: linear-gradient(90deg, var(--coral), var(--teal)); transition: width 0.3s ease;"></div>
                </div>
                <script>
                    document.querySelectorAll('.progress-bar').forEach(el => {
                        el.style.width = el.dataset.width + '%';
                    });
                </script>
            </div>
        </div>
    </div>
</div>
@endsection
