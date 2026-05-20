<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">

<title>{{ config('app.name', 'Performance System') }}</title>

<!-- Bootstrap (CDN fallback) -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+YQ4/6k1a1T6y0w+Y3jeZ0gk1q5a1K1p1Q1A1q1A1Q1" crossorigin="anonymous">

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-pM6VH8bYKgY2L59Q1+ZBzYgdW7ed1C7vD2v+bFJdv0RyxX90bj20EkuFkxLRu9O5Y1u4rQR3w2NQagdbZE+Qig==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<!-- Optional custom styles -->
<link rel="stylesheet" href="{{ asset('css/app.css') }}">

<!-- jQuery & Bootstrap JS (placed in head for legacy scripts) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
