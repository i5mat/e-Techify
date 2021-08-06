@can('is-reseller')
    @foreach($rmaInfoReseller as $rma)
        <div class="row">
            <div class="col-12 col-md-4">
                <div class="p-3 d-flex align-items-center">
                    <div class="mr-3">
                        <img src="{{ \Storage::disk('s3')->url('product/'.$rma->product_image_path) }}"
                             width="100" height="100"/>
                    </div>

                    <div class="mx-3">
                        <h5>{{ $rma->product_name }}</h5>
                        <div class="text-muted monospace">
                            {{ $rma->product_sn }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-2 d-flex align-items-center justify-content-center">
                <div class="text-center">
                    <div class="fw-bold mb-2">
                        Serial Number
                    </div>
                    <div class="font-monospace">
                        {{ $rma->sn_no }}
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-2 d-flex align-items-center justify-content-center">
                <div class="text-center">
                    <div class="fw-bold mb-2">
                        Status
                    </div>
                    <div class="font-monospace">
                        {{ $rma->status }}
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-2 d-flex align-items-center justify-content-center">
                <div class="text-center">
                    <div class="fw-bold mb-2">
                        Tracking No.
                    </div>
                    <div class="font-monospace">
                        @if($rma->tracking_no != null)
                            <a class="btn btn-outline-dark" style="font-size: 15px"
                               onclick="linkTrack(this.innerText)">{{ $rma->tracking_no }}</a>
                        @else
                            Not Available
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-2 d-flex align-items-center justify-content-center">
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
            </div>
        </div>
    @endforeach
    {{ $rmaInfoReseller->links() }}
@endcan

@can('is-distributor')
    @foreach($rmaInfoDistri as $rma)
        <div class="row">
            <div class="col-12 col-md-4">
                <div class="p-3 d-flex align-items-center">
                    <div class="mr-3">
                        <img src="{{ \Storage::disk('s3')->url('product/'.$rma->product_image_path) }}"
                             width="100" height="100"/>
                    </div>

                    <div class="mx-3">
                        <h5>{{ $rma->product_name }}</h5>
                        <div class="text-muted monospace">
                            {{ $rma->product_sn }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-2 d-flex align-items-center justify-content-center">
                <div class="text-center">
                    <div class="fw-bold mb-2">
                        Serial Number
                    </div>
                    <div class="font-monospace">
                        {{ $rma->sn_no }}
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-2 d-flex align-items-center justify-content-center">
                <div class="text-center">
                    <div class="fw-bold mb-2">
                        Status
                    </div>
                    <div class="font-monospace">
                        {{ $rma->status }}
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-2 d-flex align-items-center justify-content-center">
                <div class="text-center">
                    <div class="fw-bold mb-2">
                        Tracking No.
                    </div>
                    <div class="font-monospace">
                        @if($rma->tracking_no != null)
                            <a class="btn btn-outline-dark" style="font-size: 15px"
                               onclick="linkTrack(this.innerText)">{{ $rma->tracking_no }}</a>
                        @else
                            Not Available
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-2 d-flex align-items-center justify-content-center">
                <div class="row">
                    <div class="col">
                        <a target="_blank" href="{{ route('rma.job-sheet', $rma->id) }}"
                           class="btn btn-sm btn-primary" style="background-color:transparent; border-color: transparent;" id="tooltip_jobsheet"><i class="fa fa-file-pdf fa-2x" style="color: red"></i></a>
                    </div>
                    <div class="col">
                        <a href="{{ \Storage::disk('s3')->url('rma/'.$rma->file_path) }}" target="_blank">
                            <button class="btn btn-sm btn-info" style="background-color:transparent; border-color: transparent;" id="tooltip_invoice"><i class="fa fa-receipt fa-2x"></i></button>
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
            </div>
        </div>
    @endforeach
    {{ $rmaInfoDistri->links() }}
@endcan

@can('is-user')
    @foreach($rmaInfo as $rma)
        <div class="row" id="rma-row">
            <div class="col-12 col-md-4">
                <div class="p-3 d-flex align-items-center">
                    <div class="mr-3">
                        <img src="{{ \Storage::disk('s3')->url('product/'.$rma->product_image_path) }}"
                             width="100" height="100"/>
                    </div>

                    <div class="mx-3">
                        <h5>{{ $rma->product_name }}</h5>
                        <div class="text-muted monospace">
                            {{ $rma->product_sn }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-2 d-flex align-items-center justify-content-center">
                <div class="text-center">
                    <div class="fw-bold mb-2">
                        Serial Number
                    </div>
                    <div class="font-monospace">
                        {{ $rma->sn_no }}
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-2 d-flex align-items-center justify-content-center">
                <div class="text-center">
                    <div class="fw-bold mb-2">
                        Status
                    </div>
                    <div class="font-monospace">
                        {{ $rma->status }}
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-2 d-flex align-items-center justify-content-center">
                <div class="text-center">
                    <div class="fw-bold mb-2">
                        Tracking No.
                    </div>
                    <div class="font-monospace">
                        @if($rma->tracking_no != null)
                            <a class="btn btn-outline-dark" style="font-size: 15px"
                               onclick="linkTrack(this.innerText)">{{ $rma->tracking_no }}</a>
                        @else
                            Not Available
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-2 d-flex align-items-center justify-content-center">
                <div class="row">
                    <div class="col">
                        <a href="{{ \Storage::disk('s3')->url('rma/'.$rma->file_path) }}" target="_blank">
                            <button class="btn btn-sm btn-warning mb-1" style="background-color:transparent; border-color: transparent;" id="tooltip_invoice"><i class="fa fa-file-invoice fa-2x"></i>

                            </button>
                        </a>
                    </div>
                    <div class="col">
                        <a target="_blank" href="{{ route('rma.job-sheet', $rma->id) }}"
                           class="btn btn-sm btn-primary" style="background-color:transparent; border-color: transparent;" id="tooltip_jobsheet"><i style="color: red" class="fa fa-file-pdf fa-2x"></i>

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
            </div>
        </div>
    @endforeach
    {{ $rmaInfo->links() }}
@endcan
