@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Edit Category</h2>

    <form action="{{ route('categories.update', $category) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" class="form-control" value="{{ $category->name }}" required>
        </div>

        <div class="mb-3">
            <label>Category Image (optional)</label>
            <input type="file" name="image" class="form-control">
            @if($category->images->first())
            <p class="mt-2">Current Image:</p>
            <img src="{{ asset('storage/' . $category->images->first()->path) }}" width="150" alt="Category Image">
            @endif
        </div>

        <button class="btn btn-primary">Update</button>
    </form>

</div>
@endsection