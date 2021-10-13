<li class="nav-item {{ $active }}">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities{{ $name }}"
        aria-expanded="true" aria-controls="collapseUtilities">
        <i class="fas fa-fw {{ $icon }}"></i>
        <span>{{ $name }}</span>
    </a>
    <div id="collapseUtilities{{ $name }}" class="collapse" aria-labelledby="headingUtilities"
        data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">

            @foreach ($routes as $key => $route)
            <a class="collapse-item" href="{{ $route }}">{{ $key }}</a>
            @endforeach

        </div>
    </div>
</li>