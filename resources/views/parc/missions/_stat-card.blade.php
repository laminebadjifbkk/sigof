{{-- Carte de statistique. Variables : $title, $count, $percent, $url, $active (bool) --}}
<div class="col-6 col-sm-4 col-md-3 col-lg-2 mb-3">
    <div class="card shadow-sm text-center p-2 h-100 {{ $active ? 'border-primary border-2' : '' }}"
        style="min-height: 140px; border-radius: 10px;">
        <h6 class="card-title mb-2 text-truncate missions-title" title="{{ $title }}">
            {{ $title }}
        </h6>

        <span class="h6 mb-2" style="font-size: 1rem;">{{ $count }}</span>

        <div class="mb-2">
            <div class="progress" style="height:6px; border-radius:3px;">
                <div class="progress-bar bg-success" role="progressbar" style="width: {{ $percent }}%;"
                    aria-valuenow="{{ $percent }}" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
            <small class="text-muted">{{ $percent }}%</small>
        </div>

        <a href="{{ $url }}"
            class="btn btn-sm w-100 mt-auto {{ $active ? 'btn-primary' : 'btn-outline-primary' }}"
            style="font-size: 0.75rem;">
            Voir plus <i class="bi bi-arrow-right-short"></i>
        </a>
    </div>
</div>
