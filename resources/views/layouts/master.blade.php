<!DOCTYPE html>
<html lang="en">
@include('layouts.partials.head')
<body>

@include('layouts.partials.preventBack')

<div class="main-container">

    @include('layouts.partials.left_sidebar')

    <div class="main-content">
        @include('layouts.partials.navbar')

        <div class="container-fluid">
            @yield('content')
        </div>

        @include('layouts.partials.footer')
    </div>

    @include('layouts.partials.right_sidebar')
</div>

@include('layouts.partials.scripts')
@stack('scripts')

</body>
</html>
