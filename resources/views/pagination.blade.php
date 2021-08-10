@can('is-reseller')
    <table class="table text-center">
        <thead>
        <tr>
            <th scope="col"></th>
            <th scope="col">Name</th>
            <th scope="col">Tracking No.</th>
            <th scope="col">Status</th>
            <th scope="col">Date of Purchase</th>
            <th scope="col"></th>
        </tr>
        </thead>
        <tbody>
    @foreach($rmaInfoReseller as $rma)

            <tr>
                <td>
                    <img src="{{ \Storage::disk('s3')->url('product/'.$rma->product_image_path) }}"
                         width="100" height="100"/>
                </td>
                <td>
                    <h5>{{ $rma->product_name }}</h5>
                    <div class="text-muted monospace">
                        {{ $rma->product_sn }}
                    </div>
                </td>
                <td>
                    @if($rma->tracking_no != null)
                        <a class="btn btn-outline-dark" style="font-size: 15px"
                           onclick="linkTrack(this.innerText)">{{ $rma->tracking_no }}</a>
                    @else
                        Not Available
                    @endif
                </td>
                <td>
                    <span
                        @if($rma->status == 'Shipped')
                        class="badge rounded-pill bg-success" style="color: white"
                        @elseif($rma->status == 'Pending Receive')
                        class="badge rounded-pill bg-warning" style="color: white"
                        @else
                        class="badge rounded-pill bg-primary" style="color: white"
                    @endif
                >{{ $rma->status }}</span>
                </td>
                <td>
                    {{ date('jS F Y', strtotime($rma->date_of_purchase)) }}
                </td>
                <td>
                    <div class="row">
                        <div class="col">
                            <a target="_blank" href="{{ route('rma.job-sheet', $rma->id) }}"
                               class="btn btn-sm btn-primary" style="background-color:transparent; border-color: transparent;" id="tooltip_jobsheet"><i class="fa fa-file-pdf fa-2x" style="color: red"></i></a>
                        </div>
                        <div class="col">
                            <a href="{{ \Storage::disk('s3')->url('rma/'.$rma->file_path) }}" target="_blank">
                                <button class="btn btn-sm btn-info" style="background-color:transparent; border-color: transparent;" id="tooltip_invoice"><i class="fa fa-file-invoice fa-2x"></i></button>
                            </a>
                        </div>
                        <div class="col">
                            <a
                                href="#"
                                data-myrmaid="{{ $rma->id }}"
                                data-myprodpic="{{ $rma->product_image_path }}"
                                data-myrmastatus="{{ $rma->status }}"
                                data-myrmareason="{{ $rma->reason }}"
                                data-myrmareqat="{{ date('jS F Y H:i A', strtotime($rma->created_at)) }}"
                                data-mytrack="{{ $rma->tracking_no }}"
                                data-myresolution="{{ $rma->resolve_solution }}"
                                data-myreceive="{{ $rma->receive_at }}"
                                data-bs-toggle="modal"
                                data-bs-target="#staticRMA">
                                @can('is-reseller-distributor')
                                    <button class="btn btn-sm btn-warning" style="background-color:transparent; border-color: transparent;" id="tooltip_rma"><i
                                            class="fa fa-wrench fa-2x"></i>
                                    </button>
                                @endcan
                            </a>
                        </div>
                    </div>
                </td>
            </tr>

    @endforeach
        </tbody>
    </table>
    {{ $rmaInfoReseller->links() }}
@endcan

@can('is-distributor')
    <table class="table text-center">
        <thead>
        <tr>
            <th scope="col"></th>
            <th scope="col">Name</th>
            <th scope="col">Tracking No.</th>
            <th scope="col">Status</th>
            <th scope="col">Date of Purchase</th>
            <th scope="col"></th>
        </tr>
        </thead>
        <tbody>
    @foreach($rmaInfoDistri as $rma)

        <tr>
            <td>
                <img src="{{ \Storage::disk('s3')->url('product/'.$rma->product_image_path) }}"
                     width="100" height="100"/>
            </td>
            <td>
                <h5>{{ $rma->product_name }}</h5>
                <div class="text-muted monospace">
                    {{ $rma->product_sn }}
                </div>
            </td>
            <td>
                @if($rma->tracking_no != null)
                    <a class="btn btn-outline-dark" style="font-size: 15px"
                       onclick="linkTrack(this.innerText)">{{ $rma->tracking_no }}</a>
                @else
                    Not Available
                @endif
            </td>
            <td>
                <span
                    @if($rma->status == 'Shipped')
                        class="badge rounded-pill bg-success" style="color: white"
                    @elseif($rma->status == 'Pending Receive')
                        class="badge rounded-pill bg-warning" style="color: white"
                    @else
                        class="badge rounded-pill bg-primary" style="color: white"
                    @endif
                >{{ $rma->status }}</span>
            </td>
            <td>
                {{ date('jS F Y', strtotime($rma->date_of_purchase)) }}
            </td>
            <td>
                <div class="row">
                    <div class="col">
                        <a target="_blank" href="{{ route('rma.job-sheet', $rma->id) }}"
                           class="btn btn-sm btn-primary" style="background-color:transparent; border-color: transparent;" id="tooltip_jobsheet"><i class="fa fa-file-pdf fa-2x" style="color: red"></i></a>
                    </div>
                    <div class="col">
                        <a href="{{ \Storage::disk('s3')->url('rma/'.$rma->file_path) }}" target="_blank">
                            <button class="btn btn-sm btn-info" style="background-color:transparent; border-color: transparent;" id="tooltip_invoice"><i class="fa fa-file-invoice fa-2x"></i></button>
                        </a>
                    </div>
                    <div class="col">
                        <a
                            href="#"
                            data-myrmaid="{{ $rma->id }}"
                            data-myprodpic="{{ $rma->product_image_path }}"
                            data-myrmastatus="{{ $rma->status }}"
                            data-myrmareason="{{ $rma->reason }}"
                            data-myrmareqat="{{ date('jS F Y H:i A', strtotime($rma->created_at)) }}"
                            data-mytrack="{{ $rma->tracking_no }}"
                            data-myresolution="{{ $rma->resolve_solution }}"
                            data-myreceive="{{ $rma->receive_at }}"
                            data-bs-toggle="modal"
                            data-bs-target="#staticRMA">
                            @can('is-reseller-distributor')
                                <button class="btn btn-sm btn-warning" style="background-color:transparent; border-color: transparent;" id="tooltip_rma"><i
                                        class="fa fa-wrench fa-2x"></i>
                                </button>
                            @endcan
                        </a>
                    </div>
                </div>
            </td>
        </tr>

    @endforeach
        </tbody>
    </table>
    {{ $rmaInfoDistri->links() }}
@endcan

@can('is-user')
    <table class="table text-center">
        <thead>
        <tr>
            <th scope="col"></th>
            <th scope="col">Name</th>
            <th scope="col">Tracking No.</th>
            <th scope="col">Status</th>
            <th scope="col">Date of Purchase</th>
            <th scope="col"></th>
        </tr>
        </thead>
        <tbody>
    @foreach($rmaInfo as $rma)

        <tr>
            <td>
                <img src="{{ \Storage::disk('s3')->url('product/'.$rma->product_image_path) }}"
                     width="100" height="100"/>
            </td>
            <td>
                <h5>{{ $rma->product_name }}</h5>
                <div class="text-muted monospace">
                    {{ $rma->product_sn }}
                </div>
            </td>
            <td>
                @if($rma->tracking_no != null)
                    <a class="btn btn-outline-dark" style="font-size: 15px"
                       onclick="linkTrack(this.innerText)">{{ $rma->tracking_no }}</a>
                @else
                    Not Available
                @endif
            </td>
            <td>
                <span
                    @if($rma->status == 'Shipped')
                    class="badge rounded-pill bg-success" style="color: white"
                    @elseif($rma->status == 'Pending Receive')
                    class="badge rounded-pill bg-warning" style="color: white"
                    @else
                    class="badge rounded-pill bg-primary" style="color: white"
                    @endif
                >{{ $rma->status }}</span>
            </td>
            <td>
                {{ date('jS F Y', strtotime($rma->date_of_purchase)) }}
            </td>
            <td>
                <div class="row">
                    <div class="col">
                        <a target="_blank" href="{{ route('rma.job-sheet', $rma->id) }}"
                           class="btn btn-sm btn-primary" style="background-color:transparent; border-color: transparent;" id="tooltip_jobsheet"><i class="fa fa-file-pdf fa-2x" style="color: red"></i></a>
                    </div>
                    <div class="col">
                        <a href="{{ \Storage::disk('s3')->url('rma/'.$rma->file_path) }}" target="_blank">
                            <button class="btn btn-sm btn-info" style="background-color:transparent; border-color: transparent;" id="tooltip_invoice"><i class="fa fa-file-invoice fa-2x"></i></button>
                        </a>
                    </div>
                    <div class="col">
                        <a
                            href="#"
                            data-myrmaid="{{ $rma->id }}"
                            data-myprodpic="{{ $rma->product_image_path }}"
                            data-myrmastatus="{{ $rma->status }}"
                            data-myrmareason="{{ $rma->reason }}"
                            data-myrmareqat="{{ date('jS F Y H:i A', strtotime($rma->created_at)) }}"
                            data-mytrack="{{ $rma->tracking_no }}"
                            data-myresolution="{{ $rma->resolve_solution }}"
                            data-myreceive="{{ $rma->receive_at }}"
                            data-myrmaaddress="{{ $rma->address }}"
                            data-bs-toggle="modal"
                            data-bs-target="#staticRMA">
                            <button class="btn btn-sm btn-warning" style="background-color:transparent; border-color: transparent;" id="tooltip_rma"><i
                                    class="fa fa-wrench fa-2x"></i>
                            </button>
                        </a>
                    </div>
                </div>
            </td>
        </tr>

    @endforeach
        </tbody>
    </table>
    {{ $rmaInfo->links() }}
@endcan

<script>
    tippy('#tooltip_rma', {
        content: 'RMA Info',
    });

    @can('is-distributor')
    tippy('#tooltip_invoice', {
        content: 'Customer Invoice',
    });
    @else
    tippy('#tooltip_invoice', {
        content: 'Invoice',
    });
    @endcan

    tippy('#tooltip_jobsheet', {
        content: 'RMA Job sheet',
    });
</script>
