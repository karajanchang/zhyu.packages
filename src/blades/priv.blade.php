@extends("vendor.zhyu.layouts.main")

@push("css_plugins")
    <link rel="stylesheet" type="text/css" href="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('plugins/bower_components/sweetalert/sweetalert.css') }}" />
@endpush


@push("js")
    <script src="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('plugins/bower_components/sweetalert/sweetalert.min.js') }}"></script>
@endpush

@push("js")
    <script>
        $('body').tooltip({ selector: '[data-toggle="tooltip"]', container: 'body', animation: false });
    </script>
@endpush

@push('js')
    <script>
        $(document).ready(function(){
            $('input[name="isin"]').on('click', function(e){
                let isinObj = $(this);
                let isin = isinObj.is(':checked') ? 1 : 0;

                let resource_id = $(this).parent().find('input[name="resource_id"]').val() - 0;
                let act = $(this).parent().find('input[name="act"]').val() + '';

                let toastalter = new ToastAlter();
                $.ajax({
                    url: '{{ Request::url() }}',
                    type: 'POST',
                    data: {
                        '_token': '{{ csrf_token() }}',
                        'isin': isin,
                        'resource_id': resource_id,
                        'act': act
                    },
                    error: function (xhr) {
                        let ch = isin==1 ? false : true;
                        $(this).prop('checked', ch);
                        toastalter.fail('發生錯誤，請重試');
                    },
                    success: function (response) {
                        let ch = isin==1 ? true : false;
                        isinObj.prop('checked', ch);
                        toastalter.success('成功');
                    }
                });

                return false;
            });
        });
    </script>
@endpush


@push("css_plugins")
    <style>
        .no_list_style{
            list-style: none;
        }
        .display_inline{
            display: inline-block;
            margin-left:10px;
        }
    </style>
@endpush
@php
    $b = '新增';
    if(isset($id) && $id>0){
        $b = '修改';
    }

    $acts = ['index' => '列表', 'create' => '新增', 'edit' => '修改', 'destroy' => '刪除'];
@endphp

@section("content")
    <div class="container-fluid">
        <div class="row bg-title">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                @if(isset($id) && $id>0)
                    <h3 class="page-title">{{ __('zhyu::common.update') }} - {{ $title }}</h3>
                @else
                    <h3 class="page-title">{{ __('zhyu::common.insert') }}</h3>
                @endif
            </div>

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
                    <form class="table-responsive" id="app">


                        <!--row-->
                        <form class="row">
                            <form action="" method="post">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        @inject('resource', 'Zhyu\Model\Resource')
                                        @php
                                            $all = $resource->whereNull('parent_id')->orWhere('parent_id', 0)->orderby('orderby', 'desc')->get();
                                        @endphp

                                        <ol>
                                            @foreach($all as $rs1)
                                                <li>
                                                    <div class="title">{{ $rs1->name }}</div>
                                                    <ul>
                                                        @php
                                                            $all2 = $resource->where('parent_id', $rs1->id)->orderby('orderby', 'desc')->get();
                                                        @endphp
                                                        @foreach($all2 as $rs2)
                                                            <li class="no_list_style">* {{ $rs2->name }}
                                                                <ul>
                                                                    @foreach($acts as $act => $act_name)
                                                                        <li class="no_list_style display_inline">
                                                <span class="mt">
                                                    @php
                                                        $count = $permissions->where('resource_id', $rs2->id)->where('act', $act)->count();
                                                    @endphp
                                                    <input type="checkbox" name="isin" value="1"  @if($count>0) checked @endif/>{{ $act_name }}
                                                    <input type="hidden" name="resource_id" value="{{ $rs2->id }}" />
                                                    <input type="hidden" name="act" value="{{ $act }}" />
                                                    @csrf
                                                </span>
                                                                        </li>
                                                                    @endforeach
                                                                </ul>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </li>


                                                <p>&nbsp;</p>
                                            @endforeach
                                        </ol>
                                    </div>
                                </div>
                                @csrf
                            </form>
                            <!--/row-->

                            <div class="form-actions m-t-10" style="text-align: center">
                                <a href="javascript:;" class="btn btn-default" onclick="location.href='{{ $return_url }}'">取消，回列表</a>
                            </div>
                </div>
            </div>
        </div>
    </div>
@stop


