@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Master Upload</h1>

   <form action="{{ route('utility.master-uploadstore') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="mb-3">
        <label>Select File</label>
        <input type="file" name="file" class="form-control">
    </div>

    <button type="submit" class="btn btn-primary">
        Upload
    </button>
</form>
</div>
@endsection