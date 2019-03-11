@extends("layouts.main")

@push("css_plugins")
    <link rel="stylesheet" type="text/css" href="{{ asset('plugins/bower_components/sidebar-nav/dist/sidebar-nav.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('plugins/bower_components/datatables/jquery.dataTables.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('plugins/bower_components/sweetalert/sweetalert.css') }}" />
@endpush


@push("js")
    <script src="{{ asset('plugins/bower_components/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('plugins/bower_components/sweetalert/sweetalert.min.js') }}"></script>
@endpush

@push("js")
    <script>
        $('body').tooltip({ selector: '[data-toggle="tooltip"]', container: 'body', animation: false });
    </script>
@endpush

@section("content")
    <div class="container-fluid">
        <div class="row bg-title">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                @if(isset($id) && $id>0)
                    <h3 class="page-title">{{ __('zhyu::common.update') }} - {{ $title }}</h3> </div>
                @else
                    <h3 class="page-title">{{ __('zhyu::common.create') }}</h3> </div>
                @endif

            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                <ol class="breadcrumb">
                    <li><a href="javascript:;">{{ __($table.'.index') }}</a></li>
                    @if(isset($id) && $id>0)
                        <li class="active">{{ __('zhyu::common.update') }}</li>
                    @else
                        <li class="active">{{ __('zhyu::common.insert') }}</li>
                    @endif
                </ol>
            </div>
        </div>
        <!-- .row -->
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <div class="table-responsive">
                        @include("forms.".$table)
                    </div>
                </div>
            </div>
        </div>
        <!-- .row -->

    </div>
@stop