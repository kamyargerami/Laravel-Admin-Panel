<form action="{{route('admin.user.add')}}" id="dynamic-form" method="post">
    @csrf

    <div class="row">
        <div class="col-md-6">
            <label>نام و نام خانوادگی</label>
            <input type="text" name="name"
                   class="form-control" placeholder="نام و نام خانوادگی را به صورت کامل وارد کنید" required>
        </div>
        <div class="col-md-6">
            <label>رمز عبور</label>
            <input type="password" name="password" class="form-control ltr" placeholder="رمز عبور" required>
        </div>
    </div>

    <hr>

    <div class="row">
        <div class="col-md-6">
            <label>ایمیل</label>
            <input type="email" name="email" class="form-control ltr" required
                   placeholder="ایمیل معتبر - از این ایمیل برای اطلاع رسانی استفاده خواهد شد">
        </div>
    </div>

    <div class="mt-2">
        <label for="roles">نقش ها</label>
        <select name="roles[]" id="roles" class="select2 form-control" multiple>
            @foreach($roles as $role)
                <option value="{{$role->id}}">
                    {{__('roles.' . $role->name)}}
                </option>
            @endforeach
        </select>
    </div>
</form>
