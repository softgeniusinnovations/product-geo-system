@extends('layouts.app')

@section('content')
<div class="container">

    {{-- Action Buttons --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Products</h2>
        <div class="d-flex gap-2">
            <a href="{{ route('categories.create') }}" class="btn btn-success">+ Create Category</a>
            <a href="{{ route('products.create') }}" class="btn btn-primary">+ Create Product</a>
        </div>
    </div>

    {{-- Products Section --}}
    @if(isset($products) && $products->count() > 0)
        <div class="row">
            @foreach($products as $product)
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        {{-- Product Images --}}
                        @if($product->images->count() > 0)
                            <div id="carousel-{{ $product->id }}" class="carousel slide" data-bs-ride="carousel">
                                <div class="carousel-inner">
                                    @foreach($product->images as $key => $img)
                                        <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                                            <img src="{{ asset('storage/' . $img->path) }}" class="d-block w-100" style="height:200px; object-fit:cover;">
                                        </div>
                                    @endforeach
                                </div>
                                @if($product->images->count() > 1)
                                    <button class="carousel-control-prev" type="button" data-bs-target="#carousel-{{ $product->id }}" data-bs-slide="prev">
                                        <span class="carousel-control-prev-icon"></span>
                                    </button>
                                    <button class="carousel-control-next" type="button" data-bs-target="#carousel-{{ $product->id }}" data-bs-slide="next">
                                        <span class="carousel-control-next-icon"></span>
                                    </button>
                                @endif
                            </div>
                        @else
                            <img src="https://via.placeholder.com/300x200?text=No+Image" class="card-img-top">
                        @endif

                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">{{ $product->name }}</h5>
                            <p class="mb-1"><strong>Category:</strong> {{ $product->category?->name ?? 'N/A' }}</p>
                            <p class="mb-1"><strong>Base Price:</strong> ${{ $product->base_price }}</p>

                            {{-- GEO Prices --}}
                            @if($product->geoPrices->count() > 0)
                                <div class="mt-2">
                                    <strong>GEO Prices:</strong>
                                    <ul class="list-unstyled mb-0">
                                        @foreach($product->geoPrices as $geoPrice)
                                            <li>
                                                {{ $geoPrice->geo?->country ?? 'N/A' }}:
                                                ${{ $geoPrice->base_price_local }} (+${{ $geoPrice->delivery_cost }})
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            {{-- Like/Dislike --}}
                            <form method="POST" action="{{ route('like.toggle') }}" class="mt-2 d-flex gap-2">
                                @csrf
                                <input type="hidden" name="entity_id" value="{{ $product->id }}">
                                <input type="hidden" name="entity_type" value="product">
                                <button type="submit" name="action" value="like" class="btn btn-outline-primary flex-fill">
                                    👍 Like
                                </button>
                                <button type="submit" name="action" value="dislike" class="btn btn-outline-danger flex-fill">
                                    👎 Dislike
                                </button>
                            </form>

                            {{-- Actions --}}
                            <div class="mt-auto pt-2 d-flex justify-content-between">
                                <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route('products.destroy', $product) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <p class="text-muted">No products found.</p>
    @endif

    {{-- Image Upload Section --}}
    <h2 class="mt-5 mb-3">Upload Image</h2>
    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <form action="{{ route('images.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="image" class="form-label">Choose Image</label>
                    <input type="file" name="image" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary">Upload</button>
            </form>
        </div>
    </div>

</div>
@endsection
