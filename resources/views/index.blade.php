@extends('templates.main')

@section('content')

    @can('logged-in')
        <h1>Hi, {{ Auth::user()->name }} !</h1>

        <div class="card-group">
            <div class="card">
                <div class="card-body">
                    <div id="applewatch2"></div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div id="applewatch"></div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div id="applewatch3"></div>
                </div>
            </div>
        </div><br>

        <!-- [START] Modal to edit product [START] -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form method="POST" action="" id="myForm">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel"><img src="/image/link.png"> Product Information</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                            <input type="hidden" name="prod_id" id="prod_id" value="">
                            @csrf
                            {{ method_field('PUT') }}

                            <div class="col-md-12 text-center">
                                <img id="myImg" src="" width="200" height="200">
                            </div>

                            <label for="prod-name-label" class="col-md-4 col-form-label text-md-right">Product Name</label>

                            <div class="col-md-12">
                                <input id="prod_name" type="text" class="form-control" name="prod_name" value="" required autocomplete="prod_name" autofocus>
                            </div>

                            <label for="prod-sn-label" class="col-md-4 col-form-label text-md-right">Product SN</label>

                            <div class="col-md-12">
                                <input id="prod_sn" type="text" class="form-control" name="prod_sn" value="" required autocomplete="prod_sn" autofocus>
                            </div>

                            <label for="prod-price-label" class="col-md-4 col-form-label text-md-right">Product Price</label>

                            <div class="input-group mb-2">
                                <span class="input-group-text" id="basic-addon1"><img src="/image/malaysia.png"></span>
                                <input type="number" class="form-control" id="prod_price" name="prod_price">
                            </div>

                            <label for="prod-sn-label" class="col-md-4 col-form-label text-md-right">Product Picture</label>

                            <div class="col-md-12">
                                <input type="file" name="prod_image_update" id="prod_image_update" class="form-control">
                            </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
                </form>
            </div>
        </div>
        <!-- [END] Modal to edit product [END] -->

        <div class="card text-center">
            <div class="card-body">
                <table class="table">
                    <thead>
                    <tr>
                        <th scope="col"></th>
                        <th scope="col"></th>
                        <th scope="col">Name</th>
                        <th scope="col">Brand</th>
                        <th scope="col">Date</th>
                        <th scope="col">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($products as $prod)
                    <tr>
                        <td><img src="/storage/product/{{ $prod->product_image_path }}" width="60" height="60"></td>
                        <th scope="row">{{ $prod->product_sn }}</th>
                        <td>{{ $prod->product_name }}</td>
                        <td>{{ $prod->product_brand }}</td>
                        <td>
                            {{ date('d/m/Y H:i A', strtotime($prod->created_at ))}}
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
                                data-myprodprice="{{ $prod->product_price }}" >
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
            </div>
        </div>
    @endcan

    @guest
        <h1>Hi Homepage</h1>

        <div class="card text-white bg-primary mb-3" style="max-width: 18rem;">
            <div class="card-header">Header</div>
            <div class="card-body">
                <h5 class="card-title">Primary card title</h5>
                <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
            </div>
        </div>
        <div class="card text-white bg-info mb-3" style="max-width: 18rem;">
            <div class="card-header">Header</div>
            <div class="card-body">
                <h5 class="card-title">Info card title</h5>
                <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
            </div>
        </div>
    @endguest

    <script type="application/javascript">
        document.addEventListener('DOMContentLoaded', function () {

            /**
             * In the chart render event, add icons on top of the circular shapes
             */

            function renderIcons() {

                // Move icon
                if (!this.series[0].icon) {
                    this.series[0].icon = this.renderer.path(['M', -8, 0, 'L', 8, 0, 'M', 0, -8, 'L', 8, 0, 0, 8])
                        .attr({
                            stroke: '#303030',
                            'stroke-linecap': 'round',
                            'stroke-linejoin': 'round',
                            'stroke-width': 2,
                            zIndex: 10
                        })
                        .add(this.series[2].group);
                }
                this.series[0].icon.translate(
                    this.chartWidth / 2 - 10,
                    this.plotHeight / 2 - this.series[0].points[0].shapeArgs.innerR -
                    (this.series[0].points[0].shapeArgs.r - this.series[0].points[0].shapeArgs.innerR) / 2
                );

                // Exercise icon
                if (!this.series[1].icon) {
                    this.series[1].icon = this.renderer.path(
                        ['M', -8, 0, 'L', 8, 0, 'M', 0, -8, 'L', 8, 0, 0, 8,
                            'M', 8, -8, 'L', 16, 0, 8, 8]
                    )
                        .attr({
                            stroke: '#ffffff',
                            'stroke-linecap': 'round',
                            'stroke-linejoin': 'round',
                            'stroke-width': 2,
                            zIndex: 10
                        })
                        .add(this.series[2].group);
                }
                this.series[1].icon.translate(
                    this.chartWidth / 2 - 10,
                    this.plotHeight / 2 - this.series[1].points[0].shapeArgs.innerR -
                    (this.series[1].points[0].shapeArgs.r - this.series[1].points[0].shapeArgs.innerR) / 2
                );

                // Stand icon
                if (!this.series[2].icon) {
                    this.series[2].icon = this.renderer.path(['M', 0, 8, 'L', 0, -8, 'M', -8, 0, 'L', 0, -8, 8, 0])
                        .attr({
                            stroke: '#303030',
                            'stroke-linecap': 'round',
                            'stroke-linejoin': 'round',
                            'stroke-width': 2,
                            zIndex: 10
                        })
                        .add(this.series[2].group);
                }

                this.series[2].icon.translate(
                    this.chartWidth / 2 - 10,
                    this.plotHeight / 2 - this.series[2].points[0].shapeArgs.innerR -
                    (this.series[2].points[0].shapeArgs.r - this.series[2].points[0].shapeArgs.innerR) / 2
                );

                if (!Highcharts.theme) {
                    Highcharts.setOptions({
                        chart: {
                            backgroundColor: 'white'
                        },
                        colors: ['#F62366', '#9DFF02', '#0CCDD6'],
                        title: {
                            style: {
                                color: 'black'
                            }
                        },
                        tooltip: {
                            style: {
                                color: 'silver'
                            }
                        }
                    });
                }
            }

            Highcharts.chart('applewatch', {

                chart: {
                    type: 'solidgauge',
                    height: '110%',
                    events: {
                        render: renderIcons
                    }
                },

                title: {
                    text: 'Revenue',
                    style: {
                        fontSize: '24px'
                    }
                },

                credits: {
                    enabled: false
                },

                tooltip: {
                    borderWidth: 0,
                    backgroundColor: 'none',
                    shadow: false,
                    style: {
                        fontSize: '16px'
                    },
                    valueSuffix: '%',
                    pointFormat: '{series.name}<br><span style="font-size:2em; color: {point.color}; font-weight: bold">{point.y}</span>',
                    positioner: function (labelWidth) {
                        return {
                            x: (this.chart.chartWidth - labelWidth) / 2,
                            y: (this.chart.plotHeight / 2) + 15
                        };
                    }
                },

                pane: {
                    startAngle: 0,
                    endAngle: 360,
                    background: [{ // Track for Move
                        outerRadius: '112%',
                        innerRadius: '88%',
                        backgroundColor: Highcharts.color(Highcharts.getOptions().colors[0])
                            .setOpacity(0.3)
                            .get(),
                        borderWidth: 0
                    }, { // Track for Exercise
                        outerRadius: '87%',
                        innerRadius: '63%',
                        backgroundColor: Highcharts.color(Highcharts.getOptions().colors[1])
                            .setOpacity(0.3)
                            .get(),
                        borderWidth: 0
                    }, { // Track for Stand
                        outerRadius: '62%',
                        innerRadius: '38%',
                        backgroundColor: Highcharts.color(Highcharts.getOptions().colors[2])
                            .setOpacity(0.3)
                            .get(),
                        borderWidth: 0
                    }]
                },

                yAxis: {
                    min: 0,
                    max: 100,
                    lineWidth: 0,
                    tickPositions: []
                },

                plotOptions: {
                    solidgauge: {
                        dataLabels: {
                            enabled: false
                        },
                        linecap: 'round',
                        stickyTracking: false,
                        rounded: true
                    }
                },

                series: [{
                    name: 'Move',
                    data: [{
                        color: Highcharts.getOptions().colors[0],
                        radius: '112%',
                        innerRadius: '88%',
                        y: 80
                    }]
                }, {
                    name: 'Exercise',
                    data: [{
                        color: Highcharts.getOptions().colors[1],
                        radius: '87%',
                        innerRadius: '63%',
                        y: 65
                    }]
                }, {
                    name: 'Stand',
                    data: [{
                        color: Highcharts.getOptions().colors[2],
                        radius: '62%',
                        innerRadius: '38%',
                        y: 50
                    }]
                }]
            });

            Highcharts.chart('applewatch2', {

                chart: {
                    type: 'solidgauge',
                    height: '110%',
                    events: {
                        render: renderIcons
                    }
                },

                title: {
                    text: 'Loss',
                    style: {
                        fontSize: '24px'
                    }
                },

                credits: {
                    enabled: false
                },

                tooltip: {
                    borderWidth: 0,
                    backgroundColor: 'none',
                    shadow: false,
                    style: {
                        fontSize: '16px'
                    },
                    valueSuffix: '%',
                    pointFormat: '{series.name}<br><span style="font-size:2em; color: {point.color}; font-weight: bold">{point.y}</span>',
                    positioner: function (labelWidth) {
                        return {
                            x: (this.chart.chartWidth - labelWidth) / 2,
                            y: (this.chart.plotHeight / 2) + 15
                        };
                    }
                },

                pane: {
                    startAngle: 0,
                    endAngle: 360,
                    background: [{ // Track for Move
                        outerRadius: '112%',
                        innerRadius: '88%',
                        backgroundColor: Highcharts.color(Highcharts.getOptions().colors[0])
                            .setOpacity(0.3)
                            .get(),
                        borderWidth: 0
                    }, { // Track for Exercise
                        outerRadius: '87%',
                        innerRadius: '63%',
                        backgroundColor: Highcharts.color(Highcharts.getOptions().colors[1])
                            .setOpacity(0.3)
                            .get(),
                        borderWidth: 0
                    }, { // Track for Stand
                        outerRadius: '62%',
                        innerRadius: '38%',
                        backgroundColor: Highcharts.color(Highcharts.getOptions().colors[2])
                            .setOpacity(0.3)
                            .get(),
                        borderWidth: 0
                    }]
                },

                yAxis: {
                    min: 0,
                    max: 100,
                    lineWidth: 0,
                    tickPositions: []
                },

                plotOptions: {
                    solidgauge: {
                        dataLabels: {
                            enabled: false
                        },
                        linecap: 'round',
                        stickyTracking: false,
                        rounded: true
                    }
                },

                series: [{
                    name: 'Move',
                    data: [{
                        color: Highcharts.getOptions().colors[0],
                        radius: '112%',
                        innerRadius: '88%',
                        y: 80
                    }]
                }, {
                    name: 'Exercise',
                    data: [{
                        color: Highcharts.getOptions().colors[1],
                        radius: '87%',
                        innerRadius: '63%',
                        y: 65
                    }]
                }, {
                    name: 'Stand',
                    data: [{
                        color: Highcharts.getOptions().colors[2],
                        radius: '62%',
                        innerRadius: '38%',
                        y: 50
                    }]
                }]
            });

            Highcharts.chart('applewatch3', {

                chart: {
                    type: 'solidgauge',
                    height: '110%',
                    events: {
                        render: renderIcons
                    }
                },

                credits: {
                    enabled: false
                },

                title: {
                    text: 'Orders',
                    style: {
                        fontSize: '24px'
                    }
                },

                tooltip: {
                    borderWidth: 0,
                    backgroundColor: 'none',
                    shadow: false,
                    style: {
                        fontSize: '16px'
                    },
                    valueSuffix: '%',
                    pointFormat: '{series.name}<br><span style="font-size:2em; color: {point.color}; font-weight: bold">{point.y}</span>',
                    positioner: function (labelWidth) {
                        return {
                            x: (this.chart.chartWidth - labelWidth) / 2,
                            y: (this.chart.plotHeight / 2) + 15
                        };
                    }
                },

                pane: {
                    startAngle: 0,
                    endAngle: 360,
                    background: [{ // Track for Move
                        outerRadius: '112%',
                        innerRadius: '88%',
                        backgroundColor: Highcharts.color(Highcharts.getOptions().colors[0])
                            .setOpacity(0.3)
                            .get(),
                        borderWidth: 0
                    }, { // Track for Exercise
                        outerRadius: '87%',
                        innerRadius: '63%',
                        backgroundColor: Highcharts.color(Highcharts.getOptions().colors[1])
                            .setOpacity(0.3)
                            .get(),
                        borderWidth: 0
                    }, { // Track for Stand
                        outerRadius: '62%',
                        innerRadius: '38%',
                        backgroundColor: Highcharts.color(Highcharts.getOptions().colors[2])
                            .setOpacity(0.3)
                            .get(),
                        borderWidth: 0
                    }]
                },

                yAxis: {
                    min: 0,
                    max: 100,
                    lineWidth: 0,
                    tickPositions: []
                },

                plotOptions: {
                    solidgauge: {
                        dataLabels: {
                            enabled: false
                        },
                        linecap: 'round',
                        stickyTracking: false,
                        rounded: true
                    }
                },

                series: [{
                    name: 'Move',
                    data: [{
                        color: Highcharts.getOptions().colors[0],
                        radius: '112%',
                        innerRadius: '88%',
                        y: 80
                    }]
                }, {
                    name: 'Exercise',
                    data: [{
                        color: Highcharts.getOptions().colors[1],
                        radius: '87%',
                        innerRadius: '63%',
                        y: 65
                    }]
                }, {
                    name: 'Stand',
                    data: [{
                        color: Highcharts.getOptions().colors[2],
                        radius: '62%',
                        innerRadius: '38%',
                        y: 50
                    }]
                }]
            });
        });
    </script>
@endsection
