<div class="btn-group" data-toggle="buttons" style="margin-left: 10px">
    @foreach($options as $option => $label)
        <label class="btn btn-default btn-sm {{ Request::get('status','all') == $option ? 'active' : '' }}">
            <input type="radio" class="user-status" value="{{ $option }}">{{$label}}
        </label>
    @endforeach
</div>