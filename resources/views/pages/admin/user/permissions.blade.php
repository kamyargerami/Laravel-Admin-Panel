<div class="scrollable-y modal-height">
    <label>
        <input type="checkbox" id="select_all">
        انتخاب همه
    </label>

    <hr>


    <form action="{{route('admin.user.update-permissions',$role_id)}}" id="dynamic-form" method="post">
        @csrf
        @foreach($routes as $route)
            @if($name = $route->getName() and substr($name,0,5) == 'admin')
                <div class="form-group">
                    <label>
                        <input type="checkbox" name="permissions[]" value="{{$name}}" class="permission"
                               @if($permissions->where('name',$name)->first()) checked @endif>
                        {{substr($name,6)}}
                    </label>
                </div>
            @endif
        @endforeach
    </form>
</div>
