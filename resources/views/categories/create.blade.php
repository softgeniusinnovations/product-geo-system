@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Create Category</h2>

    <form action="{{ route('categories.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Category Image (optional)</label>
            <input type="file" name="image" class="form-control">
        </div>
        <button class="btn btn-primary">Create</button>
    </form>

</div>
@endsection