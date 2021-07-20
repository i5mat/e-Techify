<table class="table">
    <thead>
    <tr>
        <th scope="col"></th>
        <th scope="col"></th>
        <th scope="col">Name</th>
        <th scope="col">Brand</th>
        <th scope="col">Date</th>
        <th scope="col">Status</th>
        <th scope="col">Actions</th>
    </tr>
    </thead>
    <tbody>
        @foreach($products as $prod)
            <tr>
                <td><img src="{{ \Storage::disk('s3')->url('product/'.$prod->product_image_path) }}" width="60" height="60"></td>
                <th scope="row">{{ $prod->product_sn }}</th>
                <td class="font-monospace">{{ $prod->product_name }}</td>
                <td>{{ $prod->product_brand }}</td>
                <td>
                    {{ date('d/m/Y H:i A', strtotime($prod->created_at ))}}
                </td>
                <td>
                    @if ($prod->product_stock_count >= 0 && $prod->product_stock_count <= 5)
                        <span class="badge bg-danger" style="color: white">PLEASE RESTOCK</span>
                    @elseif($prod->product_stock_count >= 6 && $prod->product_stock_count <= 10)
                        <span class="badge bg-warning" style="color: white">LOW STOCK</span>
                    @elseif($prod->product_stock_count > 10)
                        <span class="badge bg-success" style="color: white">READY STOCK</span>
                    @endif
                </td>
                <td>
                    <button type="button" class="btn btn-danger" style="background-color: transparent; border: none"
                            onclick="event.preventDefault();
                                document.getElementById('delete-product-{{ $prod->id }}').submit()">
                        <img src="/image/delete.png">
                    </button>

                    <form id="delete-product-{{ $prod->id }}" action="{{ route('product.items.destroy', $prod->id) }}" method="POST" style="display: none">
                        @csrf
                        @method("DELETE")
                    </form>

                    <button
                        type="button"
                        class="btn btn-success"
                        style="background-color: transparent; border: none"
                        data-bs-toggle="modal"
                        data-bs-target="#exampleModal"
                        data-myprodid="{{ $prod->id }}"
                        data-myprodname="{{ $prod->product_name }}"
                        data-myprodsn="{{ $prod->product_sn }}"
                        data-myprodpic="{{ $prod->product_image_path }}"
                        data-myprodprice="{{ $prod->product_price }}"
                        data-myprodnewprice="{{ $prod->new_product_price }}"
                        data-myprodstock="{{ $prod->product_stock_count }}">
                        <img src="/image/edit.png">
                    </button>

                    <a href="{{ $prod->product_link }}" target="_blank">
                        <button type="button" class="btn btn-info" style="background-color: transparent; border: none">
                            <img src="/image/link.png">
                        </button>
                    </a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
{{ $products->links() }}
