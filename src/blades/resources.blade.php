

@prepend('js')
    <script>
        //var redirectAfterDelete = '/logistics';
    </script>
@endprepend


<form method="POST" action="{{ $addOrUpdateUrl }}" @submit.prevent="onSubmit" @keydown="form.errors.clear($event.target.name)">
    <div class="form-body" id="app">

        <div class="row">
            <div class="col-sm-3">
                <div class="form-group">
                    <label class="control-label">{{ __('resources.name') }}</label>
                    <input type="text" id="name" name="name" class="form-control" value="{{ old('name', @$resource->name) }}" v-model="form.name">
                    <span class="help text-danger" v-if="form.errors.has('name')" v-text="form.errors.get('name')"></span>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label">{{ __('resources.route') }}</label>
                    <input type="text" id="route" name="route" class="form-control" value="{{ old('route', @$resource->url) }}" v-model="form.route">
                    <span class="help text-danger" v-if="form.errors.has('route')" v-text="form.errors.get('route')"></span>
                </div>
            </div>
        </div>

        <div class="form-actions m-t-10">
            @php
                $b = '新增';
            if(isset($id) && $id>0){
                $b = '修改';
            }
            @endphp
            <button type="submit" class="btn btn-info m-r-10"> <i class="fa fa-check"></i> 送出{{ $b }}</button>
            <a href="javascript:;" class="btn btn-default" onclick="location.href='{{ route('resources.index') }}'">取消</a>
        </div>

    </div>

    @if(isset($id) && $id>0)
        <input type="hidden" name="id" value="{{ $id }}">
        {{ method_field('PUT') }}
    @endif


    @csrf

</form>
