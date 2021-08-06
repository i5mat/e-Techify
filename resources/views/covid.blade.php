@extends('templates.main')

@section('content')

    <h1 class="display-2 text-center mt-2"><i class="fa fa-medkit"></i></h1>

    <figure class="text-center">
        <blockquote class="blockquote">
            <p>Malaysia Vaccination Statistic</p>
        </blockquote>
        <figcaption class="blockquote-footer">
            Prepared by <cite title="Source Title">Wan Ismat</cite>
        </figcaption>
    </figure>

    <div class="card">
        <div class="card-body">
            <div id="container" style="height: 700px"></div>
        </div>
    </div>

    <script>
        $.getJSON('https://covidbucketbbc.s3-ap-southeast-1.amazonaws.com/heatdata.json?1623566954915', function(data_ncov) {
            console.log(data_ncov)

            $.each(data_ncov.data, function(i, item) {
                //const dose_complete = item[i].vakdosecomplete;
                console.log(item.nme + ' => ' + item.vakdosecomplete)
            })

            var data = [
                ['my-sa', parseInt(data_ncov.data[10].vakdosecomplete)],
                ['my-sk', parseInt(data_ncov.data[11].vakdosecomplete)],
                ['my-la', parseInt(data_ncov.data[15].vakdosecomplete)],
                ['my-pg', parseInt(data_ncov.data[9].vakdosecomplete)],
                ['my-kh', parseInt(data_ncov.data[2].vakdosecomplete)],
                ['my-sl', parseInt(data_ncov.data[12].vakdosecomplete)],
                ['my-ph', parseInt(data_ncov.data[6].vakdosecomplete)],
                ['my-kl', parseInt(data_ncov.data[14].vakdosecomplete)],
                ['my-pj', parseInt(data_ncov.data[16].vakdosecomplete)],
                ['my-pl', parseInt(data_ncov.data[8].vakdosecomplete)],
                ['my-jh', parseInt(data_ncov.data[1].vakdosecomplete)],
                ['my-pk', parseInt(data_ncov.data[7].vakdosecomplete)],
                ['my-kn', parseInt(data_ncov.data[3].vakdosecomplete)],
                ['my-me', parseInt(data_ncov.data[4].vakdosecomplete)],
                ['my-ns', parseInt(data_ncov.data[5].vakdosecomplete)],
                ['my-te', parseInt(data_ncov.data[13].vakdosecomplete)]
            ];

            var name = [
                data_ncov.data[10].nme,
                data_ncov.data[11].nme,
                data_ncov.data[15].nme,
                data_ncov.data[9].nme,
                data_ncov.data[2].nme,
                data_ncov.data[12].nme,
                data_ncov.data[6].nme,
                data_ncov.data[14].nme,
                data_ncov.data[16].nme,
                data_ncov.data[8].nme,
                data_ncov.data[1].nme,
                data_ncov.data[7].nme,
                data_ncov.data[3].nme,
                data_ncov.data[4].nme,
                data_ncov.data[5].nme,
                data_ncov.data[13].nme
            ];

            console.log(data_ncov.vakdoseupdated)

            // Create the chart
            Highcharts.mapChart('container', {
                chart: {
                    map: 'countries/my/my-all'
                },

                credits: false,

                title: {
                    text: 'STATISTIK PROGRAM IMUNISASI COVID-19 KEBANGSAAN (PICK)'
                },

                subtitle: {
                    text: 'Last updated: ' + data_ncov.vakdoseupdated
                },

                mapNavigation: {
                    enabled: true,
                    buttonOptions: {
                        verticalAlign: 'bottom'
                    }
                },

                colorAxis: {
                    min: 0
                },

                series: [{
                    data: data,
                    name: 'State',
                    states: {
                        hover: {
                            color: '#BADA55'
                        }
                    },
                    dataLabels: {
                        enabled: true,
                        format: '{point.name}'
                    }
                }, {
                    name: 'Separators',
                    type: 'mapline',
                    data: Highcharts.geojson(Highcharts.maps['countries/my/my-all'], 'mapline'),
                    color: 'silver',
                    nullColor: 'silver',
                    showInLegend: false,
                    enableMouseTracking: false
                }]
            });
        });

    </script>
@endsection
