<form action="{{route('admin.user.update',$user->id)}}" id="dynamic-form" method="post">
    @csrf

    <div class="row">
        <div class="col-md-6">
            <label>نام و نام خانوادگی</label>
            <input type="text" name="name" value="{{$user->name}}"
                   class="form-control" placeholder="نام و نام خانوادگی را به صورت کامل وارد کنید">
        </div>
        <div class="col-md-6">
            <label>رمز عبور</label>
            <input type="password" name="password"
                   class="form-control ltr"
                   placeholder="در صورت تمایل به تغییر مقدار رمز جدید وارد کنید">
        </div>
    </div>

    <hr>

    <div class="row">
        <div class="col-md-6">
            <label>موبایل</label>
            <input type="text" name="mobile" value="{{$user->mobile}}"
                   class="form-control ltr" placeholder="شماره موبایل را هر جور که راحتید وارد کنید">
        </div>

        <div class="col-md-6">
            <label>ایمیل</label>
            <input type="email" name="email" value="{{$user->email}}"
                   class="form-control ltr"
                   placeholder="ایمیل معتبر - از این ایمیل برای اطلاع رسانی استفاده خواهد شد">
        </div>
    </div>

    <div class="mt-2">
        <label for="roles">نقش ها</label>
        <select name="roles[]" id="roles" class="select2 form-control" multiple>
            @foreach($roles as $role)
                <option value="{{$role->id}}" {{$user->hasRole($role) ? 'selected' : ''}}>
                    {{__('roles.' . $role->name)}}
                </option>
            @endforeach
        </select>
    </div>
</form>
