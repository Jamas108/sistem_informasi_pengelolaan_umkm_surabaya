<div class="activities-list">
    @php
    $activities = [
        [
            'title' => 'Pelatihan Digital Marketing',
            'date' => '10 Mei 2025',
            'status' => 'upcoming', // upcoming, ongoing, completed
            'icon' => 'fa-laptop'
        ],
        [
            'title' => 'Pameran Produk UMKM',
            'date' => '20 Mei 2025',
            'status' => 'upcoming',
            'icon' => 'fa-store'
        ],
        [
            'title' => 'Workshop Packaging Product',
            'date' => '25 April 2025',
            'status' => 'completed',
            'icon' => 'fa-box'
        ],
        [
            'title' => 'Seminar Ekspor Produk UMKM',
            'date' => '15 April 2025',
            'status' => 'completed',
            'icon' => 'fa-plane'
        ],
    ];
    @endphp

    @foreach($activities as $activity)
        <div class="d-flex align-items-center mb-3">
            <div class="mr-3">
                <div class="icon-circle bg-{{ $activity['status'] == 'upcoming' ? 'primary' : ($activity['status'] == 'ongoing' ? 'warning' : 'success') }}">
                    <i class="fas {{ $activity['icon'] }} text-white"></i>
                </div>
            </div>
            <div>
                <div class="small text-gray-500">{{ $activity['date'] }}</div>
                <span class="{{ $activity['status'] == 'completed' ? 'text-decoration-line-through' : '' }}">
                    {{ $activity['title'] }}
                </span>
                @if($activity['status'] == 'upcoming' && Auth::user()->role == 'pelakuumkm')
                    <a href="#" class="btn btn-sm btn-primary ml-2">Daftar</a>
                @endif
            </div>
        </div>
    @endforeach

    <div class="text-center mt-4">
        <a href="{{ route('kegiatan.index') ?? '#' }}" class="btn btn-primary btn-sm">
            Lihat Semua Kegiatan
        </a>
    </div>
</div>

<style>
    .icon-circle {
        height: 2.5rem;
        width: 2.5rem;
        border-radius: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .text-decoration-line-through {
        text-decoration: line-through;
        color: #858796;
    }
</style>