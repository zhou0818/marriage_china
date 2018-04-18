<div class="form-group {!! !$errors->has($label) ?: 'has-error' !!}">

    <label for="{{$id}}" class="col-sm-2 control-label">{{$label}}</label>

    <div class="{{$viewClass['field']}}">

        @include('admin::form.error')

        <div id="{{$id}}" style="width: 100%; height: 100%; min-height: 600px">
            <p>{!! old($column, $value) !!}</p>
        </div>

        <input type="hidden" name="{{$name}}" value="{{ old($column, $value) }}"/>

    </div>
</div>
<style>
    .w-e-text-container {
        min-height: 600px;
    }
</style>