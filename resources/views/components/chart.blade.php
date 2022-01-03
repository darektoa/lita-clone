<div class="card shadow mb-4" @if($id)id={{ $id }}@endif>
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">{{ $title }}</h6>
    </div>
    
    <div class="card-body">
        <div class="chart-area"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand">
            <div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>
            <canvas id="{{ $canvasId }}" style="display: block; width: 482px; height: 320px;" width="482" height="320" class="chartjs-render-monitor"></canvas>
        </div>
    </div>
</div>