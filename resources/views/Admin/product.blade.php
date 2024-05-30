@extends('Admin.header')
@section('content')
    <div class="content">
        <div class="row">
            <div class="col-12">
                <div class="card card-default">
                    <div class="card-header">
                        <h2>Products Inventory</h2>
                        <a href="#" class="btn btn-primary btn-pill" data-toggle="modal" data-target="#modal-stock">Add
                            Stock</a>
                    </div>

                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        @if (session()->has('success-alert'))
                            <div class="alert alert-success">
                                {{ session()->get('success-alert') }}
                                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                            </div>
                        @endif

                        {{-- Modal Start --}}
                        <div class="modal fade" id="modal-stock" tabindex="-1" role="dialog" aria-labelledby="modal-stock"
                            aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <form action="{{ route('product.store') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Add Stock</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="form-group col-md-6">
                                                    <label for="product_name">Product Name</label>
                                                    <input type="text" class="form-control" id="product_name"
                                                        name="product_name" required>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="image">Product Image</label>
                                                    <input type="file" class="form-control" id="image" name="image"
                                                        required>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="category_id">Product Category</label>
                                                    <select name="category_id" id="category_id" class="form-control"
                                                        required>
                                                        <option value="" selected disabled>Select Category</option>
                                                        @foreach ($categories as $category)
                                                            <option value="{{ $category->id }}">
                                                                {{ $category->category_name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="total_unit">Total Unit</label>
                                                    <input type="number" class="form-control" id="total_unit"
                                                        name="total_unit" required>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="price">Price</label>
                                                    <input type="number" class="form-control" id="price" name="price"
                                                        required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-primary">Save</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        {{-- End Modal --}}

                        <div id="productsTable_wrapper" class="dataTables_wrapper no-footer">
                            <div class="dataTables_scroll">
                                <div class="dataTables_scrollHead" style="overflow: hidden; border: 0px; width: 100%;">
                                    <div class="dataTables_scrollHeadInner" style="box-sizing: content-box;">
                                        <table class="table table-hover table-product dataTable no-footer"
                                            style=" margin-left: 0px;" role="grid">
                                            <thead>
                                                <tr role="row">
                                                    <th>Product Name</th>
                                                    <th>Product Image</th>
                                                    <th>Product Category</th>
                                                    <th>Total Unit</th>
                                                    <th>Sold Unit</th>
                                                    <th>Available Unit</th>
                                                    <th>Price</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($products as $product)
                                                    <tr role="row">
                                                        <td class="text-center">{{ $product->product_name }}</td>
                                                        <td class="text-center"><img
                                                                src="{{ asset('images/' . $product->image) }}"
                                                                alt="{{ $product->product_name }}"
                                                                style="width: 100%; height: auto;"></td>
                                                        <td class="text-center">{{ $product->category->category_name }}</td>
                                                        <td class="text-center">{{ $product->total_unit }}</td>
                                                        <td class="text-center">{{ $product->sold_unit ?? '-' }}</td>
                                                        <td class="text-center">{{ $product->available_unit ?? '-' }}</td>
                                                        <td class="text-right">₹{{ $product->price }}</td>
                                                        <td style="display: flex;justify-content:center;">
                                                            <a href="{{ route('product.edit', $product->id) }}"><i
                                                                    class="mdi mdi-pencil"></i></a>
                                                            <form action="{{ route('product.destroy', $product->id) }}"
                                                                method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" style="margin-left: 10px;"
                                                                    onclick="return confirm('Are you sure you want to delete this product?')">
                                                                    <i class="mdi mdi-trash-can text-danger"></i>
                                                                </button>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <!-- Additional table content -->
                            </div>
                        </div>

                    </div>
                    @if ($products->hasPages())
                        <div class="card-footer text-right">
                            {{ $products->links() }}
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
@endsection
