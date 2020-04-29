@extends("vendor.zhyu.layouts.main")

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
        var table = '';
        {!! $datatablesService->js() !!}

        @if(isset($errors) && count($errors))
        @php
            $errorString = '';
            foreach($errors->all() as $err){
                $errorString.=$err;
            }
        @endphp
        $.toast({
            heading: '失敗!',
            text: '{{ $errorString }}',
            position: 'top-right',
            loaderBg: '#ff6849',
            icon: 'error',
            hideAfter: 6000,
            stack: 6
        });
        @endif
    </script>
@endpush

@section("content")
    <div class="container-fluid">
        <div class="row bg-title">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h3 class="page-title">{{ __('zhyu::common.list') }} <span>&gt;&gt;{!! $title !!}</span></h3>
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                <ol class="breadcrumb">
                    <li><a href="javascript:;">{{ __($table.'.index') }}</a></li>
                    <li class="active">{{ __('zhyu::common.list') }}</li>
                </ol>
            </div>
        </div>
        <!-- .row -->
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    @includeFirst([ @$box_blade, 'zhyu::blocks.box1'])

                    <div class="table-responsive" id="app">
                        {!! $datatablesService->table() !!}
                    </div>
                </div>
            </div>
        </div>
        <!-- .row -->
    </div>
@stop