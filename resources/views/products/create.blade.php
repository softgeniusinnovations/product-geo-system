@extends('layouts.app')

@section('content')
<div class="container">
    <h2>{{ isset($product) ? 'Edit Product' : 'Create Product' }}</h2>

    <form action="{{ isset($product) ? route('products.update', $product) : route('products.store') }}" 
          method="POST" enctype="multipart/form-data">
        @csrf
        @if(isset($product))
            @method('PUT')
        @endif

        <!-- Product Name -->
        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" class="form-control" 
                   value="{{ $product->name ?? old('name') }}" required>
        </div>

        <!-- Base Price -->
        <div class="mb-3">
            <label>Base Price</label>
            <input type="number" step="0.01" name="base_price" class="form-control" 
                   value="{{ $product->base_price ?? old('base_price') }}" required>
        </div>

        <!-- Category -->
        <div class="mb-3">
            <label>Category</label>
            <select name="category_id" class="form-select" required>
                <option value="">-- Select Category --</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" 
                        {{ isset($product) && $product->category_id == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- GEO Prices -->
        <h4>GEO Prices</h4>
        @foreach($geos as $geo)
            <div class="row mb-2">
                <div class="col-md-6">
                    <label>{{ $geo->country }} Delivery Cost</label>
                    <input type="hidden" name="geo_id[]" value="{{ $geo->id }}">
                    <input type="number" step="0.01" name="delivery_cost[]" class="form-control"
                        value="{{ $geoPrices[$geo->id]->delivery_cost ?? 0 }}">
                </div>
                <div class="col-md-6">
                    <label>{{ $geo->country }} Base Price Local</label>
                    <input type="number" step="0.01" name="base_price_local[]" class="form-control"
                        value="{{ $geoPrices[$geo->id]->base_price_local ?? $product->base_price ?? 0 }}">
                </div>
            </div>
        @endforeach

        <!-- Multiple Images Upload -->
        <div class="mb-3">
            <label>Product Images (Max 10)</label>
            <input type="file" name="images[]" class="form-control" id="images" multiple accept="image/*">
        </div>

        <!-- Preview Thumbnails -->
        <div id="preview" class="d-flex flex-wrap gap-2">
            @if(isset($product) && $product->images)
                @foreach($product->images as $img)
                    <div class="position-relative">
                        <img src="{{ asset('storage/' . $img->path) }}" width="100" class="border">
                    </div>
                @endforeach
            @endif
        </div>

        <button class="btn btn-primary mt-3">{{ isset($product) ? 'Update' : 'Create' }}</button>
    </form>
</div>


<script>
document.getElementById('images').addEventListener('change', function(event){
    const preview = document.getElementById('preview');
    preview.innerHTML = '';

    const files = event.target.files;
    if(files.length > 10){
        alert("You can upload max 10 images");
        event.target.value = ''; 
        return;
    }

    Array.from(files).forEach(file => {
        const reader = new FileReader();
        reader.onload = function(e){
            const img = document.createElement('img');
            img.src = e.target.result;
            img.width = 100;
            img.className = 'border';
            preview.appendChild(img);
        }
        reader.readAsDataURL(file);
    });
});
</script>
@endsection
