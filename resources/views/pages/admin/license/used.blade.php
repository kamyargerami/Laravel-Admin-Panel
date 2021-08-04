<div class="p-2 bg-light border radius-10 mb-3">
    <div class="row">
        <div class="col-6">
            نام:
            {{$license->first_name}}
        </div>
        <div class="col-6">
            نام خانوادگی:
            {{$license->last_name}}
        </div>
        <div class="col-6">
            تلفن:
            {{$license->phone}}
        </div>
        <div class="col-6">
            ایمیل:
            {{$license->email}}
        </div>
        <div class="col-6">
            کشور:
            {{$license->country}}
        </div>
        <div class="col-6">
            شهر:
            {{$license->city}}
        </div>
        <div class="col-md">
            شرکت:
            {{$license->company}}
        </div>
    </div>
</div>
<div class="table-responsive">
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>#</th>
            <th>ورژن</th>
            <th>نام دستگاه</th>
            <th>شماره شناسایی</th>
            <th>تاریخ ایجاد</th>
        </tr>
        </thead>
        <tbody>
        @foreach($used_licenses as $used)
            <tr>
                <td>{{$used->id}}</td>
                <td>{{$used->version}}</td>
                <td>{{$used->device_name}}</td>
                <td>{{$used->fingerprint}}</td>
                <td>{{\Morilog\Jalali\Jalalian::fromDateTime($used->created_at)}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
