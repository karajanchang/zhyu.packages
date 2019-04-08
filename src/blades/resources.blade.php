

@prepend('js')
    <script>
        //var redirectAfterDelete = '/logistics';
    </script>
@endprepend

@php
    $b = '新增';
if(isset($id) && $id>0){
    $b = '修改';
}

$query = '?query=parent_id,whereNull';
if(isset($resource->parent_id) && $resource->parent_id>0){
    $query = '?query=parent_id,=,'.$resource->parent_id;
}
@endphp


<form method="POST" action="{{ $addOrUpdateUrl.$query }}" @submit.prevent="onSubmit" @keydown="form.errors.clear($event.target.name)">
    <div class="form-body" id="app">

        <div class="row">
            <div class="col-sm-3">
                <div class="form-group">
                    <label class="control-label">{{ __('resources.parent') }}</label>
                    @inject('resource', 'Zhyu\Model\Resource')
                    @php
                        $all = $resource->whereNull('parent_id')->pluck('name', 'id');
                    @endphp
                    <select id="parent_id" v-model="form.parent_id" class="form-control">
                        <option value="">-</option>
                        @foreach($all as $key => $val)
                            <option value="{{ $key }}" @if(isset($resource->parent_id) && $resource->parent_id==$key) selected @endif>{{ $val }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>


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
            <div class="col-sm-3">
                <div class="form-group">
                    <label class="control-label">{{ __('resources.route') }}</label>
                    <input type="text" id="route" name="route" class="form-control" value="{{ old('route', @$resource->route) }}" v-model="form.route">
                    <span class="help text-danger" v-if="form.errors.has('route')" v-text="form.errors.get('route')"></span>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-3">
                <div class="form-group">
                    <label class="control-label">{{ __('resources.orderby') }}&nbsp;&nbsp;<span style="color:grey">(數字大排前)</span></label>
                    <input type="text" id="orderby" name="orderby" class="form-control" value="{{ old('orderby', @$resource->orderby) }}" v-model="form.orderby">
                    <span class="help text-danger" v-if="form.errors.has('orderby')" v-text="form.errors.get('orderby')"></span>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-3">
                <div class="form-group">
                    <label class="control-label">{{ __('resources.icon_css') }}</label>
                    <input type="text" id="icon_css" name="icon_css" class="form-control" value="{{ old('icon_css', @$resource->icon_css) }}" v-model="form.icon_css">
                    <span class="help text-danger" v-if="form.errors.has('icon_css')" v-text="form.errors.get('icon_css')"></span>
                </div>
            </div>
        </div>


        <div class="form-actions m-t-10">

            <button type="submit" class="btn btn-info m-r-10"> <i class="fa fa-check"></i> 送出{{ $b }}</button>
            <a href="javascript:;" class="btn btn-default" onclick="location.href='{{ route('admin.resources.index').$query }}'">取消</a>
        </div>

    </div>

    @if(isset($id) && $id>0)
        <input type="hidden" name="id" value="{{ $id }}">
        {{ method_field('PUT') }}
    @endif


    @csrf

</form>
