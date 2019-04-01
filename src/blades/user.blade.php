

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

@endphp


<form method="POST" action="{{ $addOrUpdateUrl }}" @submit.prevent="onSubmit" @keydown="form.errors.clear($event.target.name)">
    <div class="form-body" id="app">

        <div class="row">
            <div class="col-sm-3">
                <div class="form-group">
                    <label class="control-label">{{ __('resources.parent') }}</label>
                    @inject('usergroup', 'App\Usergroup')
                    @php
                        $all = $usergroup->whereNull('parent_id')->orWhere('parent_id', 0)->pluck('name', 'id');
                    @endphp
                    <select id="usergroup_id" v-model="form.usergroup_id" class="form-control">
                        <option value="">-</option>
                        @foreach($all as $key => $val)
                            <option value="{{ $key }}">{{ $val }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>



        <div class="row">
            <div class="col-sm-3">
                <div class="form-group">
                    <label class="control-label">{{ __('user.name') }}</label>
                    <input type="text" id="name" class="form-control" v-model="form.name">
                    <span class="help text-danger" v-if="form.errors.has('name')" v-text="form.errors.get('name')"></span>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-3">
                <div class="form-group">
                    <label class="control-label">{{ __('user.nickname') }}</label>
                    <input type="text" id="nickname" class="form-control" v-model="form.nickname">
                    <span class="help text-danger" v-if="form.errors.has('nickname')" v-text="form.errors.get('nickname')"></span>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-3">
                <div class="form-group">
                    <label class="control-label">{{ __('user.email') }}</label>
                    <input type="text" id="email" class="form-control" v-model="form.email">
                    <span class="help text-danger" v-if="form.errors.has('email')" v-text="form.errors.get('email')"></span>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-3">
                <div class="form-group">
                    <label class="control-label">{{ __('user.is_online') }}</label>
                    <input type="checkbox" id="is_online" class="form-control" v-model="form.is_online" :true-value=1 :false-value=0>
                    <span class="help text-danger" v-if="form.errors.has('is_online')" v-text="form.errors.get('is_online')"></span>
                </div>
            </div>
        </div>




        <div class="form-actions m-t-10">

            <button type="submit" class="btn btn-info m-r-10"> <i class="fa fa-check"></i> 送出{{ $b }}</button>
            <a href="javascript:;" class="btn btn-default" onclick="location.href='{{ route($route.'.index') }}'">取消</a>
        </div>

    </div>

    @if(isset($id) && $id>0)
        <input type="hidden" name="id" value="{{ $id }}">
        {{ method_field('PUT') }}
    @endif


    @csrf

</form>
