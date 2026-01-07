<div class="cars-grid">
    @forelse($cars as $car)
        <div class="car-card">
            <div class="car-image-container">
                @if($car->image)
                    <img src="{{ asset('storage/' . $car->image) }}" alt="{{ $car->model->name }}" class="car-image">
                @else
                    <div class="no-image">No Image</div>
                @endif
                <div class="car-badge {{ $car->status }}">{{ ucfirst($car->status) }}</div>
            </div>
            <div class="car-details">
                <div class="car-header">
                    <h3>{{ $car->model->brand }} {{ $car->model->name }}</h3>
                    <span class="car-year">{{ $car->year }}</span>
                </div>
                <div class="car-price">
                    {{ number_format($car->price_per_day, 2) }} <span style="font-size: 1rem; font-weight: 700; color: var(--primary);">MAD</span> <span>/ day</span>
                </div>
                <div class="car-actions">
                    <button class="btn-icon btn-edit" data-id="{{ $car->id }}" onclick="openEditModal({{ $car->id }})">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                    </button>
                    <button class="btn-icon btn-delete" data-id="{{ $car->id }}" onclick="deleteCar({{ $car->id }})">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                    </button>
                </div>
            </div>
        </div>
    @empty
        <div class="no-results">
            <p>No cars found.</p>
        </div>
    @endforelse
</div>

<div class="pagination-container">
    {!! $cars->links('pagination::bootstrap-4') !!}
</div>
