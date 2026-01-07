<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Car Rental Management</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary: #4f46e5;
            --primary-hover: #4338ca;
            --bg-color: #f3f4f6;
            --card-bg: #ffffff;
            --text-main: #111827;
            --text-sub: #6b7280;
            --border-color: #e5e7eb;
            --success: #10b981;
            --danger: #ef4444;
            --warning: #f59e0b;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: var(--bg-color);
            color: var(--text-main);
            margin: 0;
            padding: 0;
            -webkit-font-smoothing: antialiased;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px 20px;
        }

        /* Header */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 40px;
        }
        .page-title h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin: 0;
            background: linear-gradient(135deg, var(--primary) 0%, #818cf8 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .add-btn {
            background-color: var(--primary);
            color: white;
            padding: 12px 24px;
            border-radius: 12px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px -1px rgba(79, 70, 229, 0.2);
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .add-btn:hover {
            background-color: var(--primary-hover);
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(79, 70, 229, 0.3);
        }

        /* Controls */
        .controls-bar {
            background: var(--card-bg);
            padding: 20px;
            border-radius: 16px;
            display: flex;
            gap: 20px;
            margin-bottom: 30px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            flex-wrap: wrap;
        }
        .search-box {
            flex: 2;
            min-width: 250px;
        }
        .filter-box {
            flex: 1;
            min-width: 200px;
        }
        .form-input, .form-select {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid var(--border-color);
            border-radius: 10px;
            font-family: inherit;
            font-size: 0.95rem;
            transition: border-color 0.2s;
            outline: none;
            box-sizing: border-box; /* Fix sizing issues */
        }
        .form-input:focus, .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }

        /* Grid */
        .cars-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 24px;
        }
        
        /* Card */
        .car-card {
            background: var(--card-bg);
            border-radius: 16px;
            overflow: hidden;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            position: relative;
            display: flex;
            flex-direction: column;
        }
        .car-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        }
        .car-image-container {
            height: 200px;
            background-color: #e0e7ff;
            position: relative;
            overflow: hidden;
        }
        .car-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }
        .car-card:hover .car-image {
            transform: scale(1.05);
        }
        .no-image {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100%;
            color: var(--text-sub);
            font-weight: 500;
        }
        .car-badge {
            position: absolute;
            top: 12px;
            right: 12px;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(4px);
        }
        .car-badge.available { color: var(--success); }
        .car-badge.rented { color: var(--danger); }
        .car-badge.maintenance { color: var(--warning); }

        .car-details {
            padding: 20px;
            flex-grow: 1; /* Ensure details take up remaining space */
            display: flex;
            flex-direction: column;
        }
        .car-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 8px;
        }
        .car-header h3 {
            margin: 0;
            font-size: 1.25rem;
            font-weight: 600;
        }
        .car-year {
            font-size: 0.85rem;
            color: var(--text-sub);
            background: var(--bg-color);
            padding: 4px 8px;
            border-radius: 6px;
        }
        .car-price {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 20px;
        }
        .car-price span {
            font-size: 0.9rem;
            color: var(--text-sub);
            font-weight: 400;
        }
        .car-actions {
            display: flex;
            gap: 12px;
            margin-top: auto; /* Push actions to the bottom */
        }
        .btn-icon {
            flex: 1;
            padding: 10px;
            border-radius: 8px;
            border: 1px solid var(--border-color);
            background: transparent;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-sub);
        }
        .btn-edit:hover {
            background: #e0e7ff;
            color: var(--primary);
            border-color: #c7d2fe;
        }
        .btn-delete:hover {
            background: #fee2e2;
            color: var(--danger);
            border-color: #fecaca;
        }

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            backdrop-filter: blur(5px);
            align-items: center; /* Center Vertically */
            justify-content: center; /* Center Horizontally */
        }
        .modal.show {
            display: flex;
        }
        .modal-content {
            background-color: var(--card-bg);
            border-radius: 20px;
            width: 100%;
            max-width: 500px;
            padding: 32px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            animation: modalSlide 0.3s ease-out;
            max-height: 90vh; /* Prevent overflow */
            overflow-y: auto;
        }
        @keyframes modalSlide {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }
        .modal-header h2 {
            margin: 0;
            font-size: 1.5rem;
        }
        .close-btn {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: var(--text-sub);
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--text-main);
        }
        .submit-btn {
            width: 100%;
            background-color: var(--primary);
            color: white;
            padding: 14px;
            border-radius: 12px;
            border: none;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s;
        }
        .submit-btn:hover {
            background-color: var(--primary-hover);
        }

        /* Pagination */
        .pagination-container {
            margin-top: 40px;
            display: flex;
            justify-content: center;
        }
        /* Bootstrap pagination overrides */
        .pagination {
            display: flex;
            list-style: none;
            padding: 0;
            gap: 8px;
        }
        .page-link {
            padding: 10px 16px;
            border-radius: 8px;
            background: white;
            border: 1px solid var(--border-color);
            color: var(--text-main);
            text-decoration: none;
            transition: all 0.2s;
        }
        .page-item.active .page-link {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
        }
        .page-link:hover {
            background: #f9fafb;
        }

        .no-results {
            grid-column: 1 / -1;
            text-align: center;
            padding: 60px;
            background: white;
            border-radius: 16px;
            color: var(--text-sub);
        }
    </style>
</head>
<body>

<div class="container">
    <div class="page-header">
        <div class="page-title">
            <h1>ELACHIRI CARS</h1>
            <p style="color: var(--text-sub); margin-top: 8px; font-weight: 500;">Manage your vehicles efficiently</p>
        </div>
        <button class="add-btn" onclick="openCreateModal()">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            Add New Car
        </button>
    </div>

    <div class="controls-bar">
        <div class="search-box">
            <input type="text" id="search" class="form-input" placeholder="Search by brand or model...">
        </div>
        <div class="filter-box">
            <select id="model_filter" class="form-select">
                <option value="all">All Models</option>
                @foreach($models as $model)
                    <option value="{{ $model->id }}">{{ $model->brand }} {{ $model->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div id="car-list-container">
        @include('cars.partials.list', ['cars' => $cars])
    </div>
</div>

<!-- Create Modal -->
<div id="createModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Add New Car</h2>
            <button class="close-btn" onclick="closeCreateModal()">&times;</button>
        </div>
        <form id="createForm" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label>Model</label>
                <select name="model_id" class="form-select" required>
                    <option value="">Select Model</option>
                    @foreach($models as $model)
                        <option value="{{ $model->id }}">{{ $model->brand }} {{ $model->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Year</label>
                <input type="number" name="year" class="form-input" value="{{ date('Y') }}" required>
            </div>
            <div class="form-group">
                <label>Price per Day (MAD)</label>
                <input type="number" name="price_per_day" class="form-input" step="0.01" required>
            </div>
            <div class="form-group">
                <label>Status</label>
                <select name="status" class="form-select" required>
                    <option value="available">Available</option>
                    <option value="rented">Rented</option>
                    <option value="maintenance">Maintenance</option>
                </select>
            </div>
            <div class="form-group">
                <label>Image</label>
                <input type="file" name="image" class="form-input" accept="image/*" required>
            </div>
            <button type="submit" class="submit-btn" id="createBtn">Add Vehicle</button>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Edit Car</h2>
            <button class="close-btn" onclick="closeEditModal()">&times;</button>
        </div>
        <form id="editForm" enctype="multipart/form-data">
            @csrf
            <input type="hidden" id="edit_car_id">
            
            <div class="form-group">
                <label>Model</label>
                <select name="model_id" id="edit_model_id" class="form-select" required>
                    @foreach($models as $model)
                        <option value="{{ $model->id }}">{{ $model->brand }} {{ $model->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Year</label>
                <input type="number" name="year" id="edit_year" class="form-input" required>
            </div>
            <div class="form-group">
                <label>Price per Day (MAD)</label>
                <input type="number" name="price_per_day" id="edit_price" class="form-input" step="0.01" required>
            </div>
            <div class="form-group">
                <label>Status</label>
                <select name="status" id="edit_status" class="form-select" required>
                    <option value="available">Available</option>
                    <option value="rented">Rented</option>
                    <option value="maintenance">Maintenance</option>
                </select>
            </div>
            <div class="form-group">
                <label>Image (Leave blank to keep current)</label>
                <input type="file" name="image" class="form-input" accept="image/*">
            </div>
            <button type="submit" class="submit-btn" id="updateBtn">Save Changes</button>
        </form>
    </div>
</div>

<script>
    // State
    const baseUrl = '/cars';
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Fetch Logic
    function fetchCars(page = 1) {
        const query = document.getElementById('search').value;
        const modelId = document.getElementById('model_filter').value;
        const url = `${baseUrl}/fetch?page=${page}&search=${encodeURIComponent(query)}&model_id=${modelId}`;

        fetch(url, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(response => response.text())
        .then(html => {
            document.getElementById('car-list-container').innerHTML = html;
        })
        .catch(err => console.error('Error loading cars:', err));
    }

    // Event Listeners
    document.getElementById('search').addEventListener('input', () => fetchCars(1));
    document.getElementById('model_filter').addEventListener('change', () => fetchCars(1));
    
    // Pagination Delegation
    document.addEventListener('click', function(e) {
        if (e.target.closest('.pagination .page-link')) {
            e.preventDefault();
            const url = e.target.closest('.page-link').href;
            const page = new URL(url).searchParams.get('page');
            fetchCars(page);
        }
    });

    // Modals
    const createModal = document.getElementById('createModal');
    const editModal = document.getElementById('editModal');

    window.openCreateModal = () => createModal.classList.add('show');
    window.closeCreateModal = () => createModal.classList.remove('show');
    window.closeEditModal = () => editModal.classList.remove('show');

    // Close on click outside
    window.onclick = function(event) {
        if (event.target == createModal) closeCreateModal();
        if (event.target == editModal) closeEditModal();
    }

    // Create
    document.getElementById('createForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        const btn = document.getElementById('createBtn');
        btn.textContent = 'Saving...';
        btn.disabled = true;

        fetch(baseUrl, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': csrfToken
            }
        })
        .then(res => res.json())
        .then(data => {
            if(data.success) {
                closeCreateModal();
                fetchCars(); // Refresh list
                this.reset();
            } else {
                alert('Error creating car');
            }
        })
        .catch(err => alert('Error: ' + err))
        .finally(() => {
            btn.textContent = 'Add Vehicle';
            btn.disabled = false;
        });
    });

    // Edit - Open & Populate
    window.openEditModal = (id) => {
        // Fetch car details
        fetch(`${baseUrl}/${id}`)
            .then(res => res.json())
            .then(data => {
                document.getElementById('edit_car_id').value = data.id;
                document.getElementById('edit_model_id').value = data.model_id;
                document.getElementById('edit_year').value = data.year;
                document.getElementById('edit_price').value = data.price_per_day;
                document.getElementById('edit_status').value = data.status;
                // We cannot set file input value
                editModal.classList.add('show');
            });
    };

    // Update
    document.getElementById('editForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const id = document.getElementById('edit_car_id').value;
        const formData = new FormData(this);
        // Note: For Laravel to handle files in PUT, we actually use POST with _method=PUT/PATCH, 
        // OR we can just use POST and a specific route, but resource route uses PUT/PATCH.
        // We added <input type="hidden" name="_method" value="POST"> - wait, I put POST there? 
        // It should be PUT or PATCH. Let's fix it dynamically or just use POST with _method=PUT field.
        // Actually, PHP standard FormData upload only works with POST.
        // So we strictly send POST, and include _method = PUT in the FormData.
        formData.append('_method', 'PUT');

        const btn = document.getElementById('updateBtn');
        btn.textContent = 'Updating...';
        btn.disabled = true;

        fetch(`${baseUrl}/${id}`, {
            method: 'POST', // Important: Must be POST for FormData with files
            body: formData,
            headers: {
                'X-CSRF-TOKEN': csrfToken
            }
        })
        .then(res => res.json())
        .then(data => {
            if(data.success) {
                closeEditModal();
                fetchCars(); // Keep current page ideally, but for now refresh to 1 is okay or we can read current page
            } else {
                alert('Error updating car');
            }
        })
        .catch(err => alert('Error: ' + err))
        .finally(() => {
            btn.textContent = 'Save Changes';
            btn.disabled = false;
        });
    });

    // Delete
    window.deleteCar = (id) => {
        if(confirm('Are you sure you want to delete this car?')) {
            fetch(`${baseUrl}/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Content-Type': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                if(data.success) {
                    fetchCars();
                } else {
                    alert('Error deleting car');
                }
            })
            .catch(err => alert('Error: ' + err));
        }
    }
</script>

</body>
</html>
