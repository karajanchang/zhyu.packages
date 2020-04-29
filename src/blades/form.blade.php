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

@php
    try{
        $table = ${$model_name}->getTable();
        $columns = Schema::getColumnListing($table);
        $tmps = [];
        foreach($columns as $column){
			if($column!='password'){
                $tmps[] = $column.": `".old($column, ${$model_name}->$column)."`";
            }else{
				$tmps[] = "password: ``";
				$tmps[] = "password_confirmation: ``";
            }
        }
        $tmp_str = join(',', $tmps);

    }catch (\Exception $e){
        throw new \Exception($e->getMessage());
    }
@endphp
@push("js")
    <script>
        const toastAlter = new ToastAlter();

        const app = new Vue({
            el: '#app',
            data(){
                return {
                    form: new Form({
                        {{ $tmp_str }}
                    })
                }
            },
            methods: {
                onSubmit() {
                            @if(isset($id) && $id>0)
                    let res = this.form.put('{{ $addOrUpdateUrl}}')
                            .then(response => {
                                toastAlter.success('資料已更新完成');

                                try{
                                    location.href = redirectAfterPut;
                                }catch(e) {
                                }
                            });
                            @else
                    let res = this.form.post('{{ $addOrUpdateUrl}}')
                            .then(response => {
                                toastAlter.success('資料已新增完成');

                                try{
                                    location.href = redirectAfterPost;
                                }catch(e) {
                                    location.href = '{{ route($route.'.index') }}';
                                }
                            });
                    @endif
                    res.catch( errors => {
                            toastAlter.fail(errors.message);
                        }
                    )
                }
            }
        });
    </script>
@endpush

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
                    <div class="table-responsive" id="app">
                        @includeFirst( [ "vendor.zhyu.$table", "blades.$table"])
                    </div>
                </div>
            </div>
        </div>
        <!-- .row -->

    </div>
@stop