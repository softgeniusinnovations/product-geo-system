@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Products</h2>
    <a href="{{ route('products.create') }}" class="btn btn-primary mb-3">Add Product</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Name</th>
                <th>Base Price</th>
                <th>Category</th>
                <th>Image</th>
                <th>GEO Prices</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
            <tr>
                <td>{{ $product->name }}</td>
                <td>${{ $product->base_price }}</td>
                <td>{{ $product->category?->name }}</td>
                <td>
                    @if($product->images->first())
                        <img src="{{ asset('storage/' . $product->images->first()->path) }}" width="100">
                    @endif
                </td>
                <td>
                    @foreach($product->geoPrices as $geoPrice)
                        <strong>{{ $geoPrice->geo?->country ?? 'N/A' }}</strong>: ${{ $geoPrice->base_price_local }} (+${{ $geoPrice->delivery_cost }})<br>
                    @endforeach
                </td>
                <td>
                    <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('products.destroy', $product) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
