@extends('templates.main')

@section('content')
    <h1 class="display-2 text-center">Receipt Finder <i class="fa fa-hand-lizard-o"></i></h1>

    <figure class="text-center">
        <blockquote class="blockquote">
            <p>A well-known quote, contained in a blockquote element.</p>
        </blockquote>
        <figcaption class="blockquote-footer">
            Someone famous in <cite title="Source Title">Source Title</cite>
        </figcaption>
    </figure>

    <div class="card">
        <div class="card-body">
            <input class="form-control mb-3" onkeyup="myFunction()" type="text" id="myInput" placeholder="Search either order status or invoice ID" title="Type in a name">

            <div class="list-group mb-2" id="myUL">
                @foreach($getAll as $gA)
                    <a href="{{ route('order.purchase.receipt', $gA->order_id) }}" target="_blank" class="list-group-item list-group-item-action">
                        <div class="d-flex w-100 justify-content-between">
                            <h5 class="mb-1">{{ $gA->receipt_no }}</h5>
                            @if($gA->order_status == 'To Ship')
                                <small><i class="fa fa-truck"></i> {{ $gA->order_status }}</small>
                            @else
                                <small><i class="fa fa-home"></i> {{ $gA->order_status }}</small>
                            @endif
                        </div>
                        <small>{{ date('d M Y h:i A', strtotime($gA->created_at)) }}</small>
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    <script>
        function myFunction() {
            var input, filter, ul, li, a, i, txtValue;
            input = document.getElementById("myInput");
            filter = input.value.toUpperCase();
            ul = document.getElementById("myUL");
            li = ul.getElementsByTagName("a");
            for (i = 0; i < li.length; i++) {
                a = li[i].getElementsByTagName("div")[0];
                txtValue = a.textContent || a.innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    li[i].style.display = "";
                } else {
                    li[i].style.display = "none";
                }
            }
        }
    </script>
@endsection
