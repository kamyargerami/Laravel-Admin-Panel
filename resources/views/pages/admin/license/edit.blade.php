<form action="{{route('admin.license.update', $license->id)}}" id="dynamic-form" method="post">
    @csrf

    <div class="form-group mb-3">
        <label for="type">
            نوع
            <label for="new_type">
                نوع
                <i class="text-danger fa fa-warning ms-2" data-bs-toggle="tooltip"
                   data-bs-placement="right"
                   title="اگر این لایسنس قبلا استفاده شده باشد، در صورت تغییر این فیلد، می بایست مقدار تاریخ انقضا لایسنس را هم تغییر دهید، api با استفاده از این مورد انقضای یک لایسنس را تشخیص میدهد."></i>
            </label>
        </label>
        <select name="type" class="form-select mt-1" id="type" required>
            @foreach(\App\Models\License::Types as $type)
                @continue($type == 'trial')
                <option value="{{$type}}" {{$license->type == $type ? 'selected' : ''}}>
                    {{__('types.license.' . $type)}}
                </option>
            @endforeach
        </select>
    </div>

    <div class="form-group mb-3">
        <label for="product_id">محصول</label>
        <select name="product_id" class="form-select mt-1" id="product_id" required>
            @foreach($products as $product)
                <option value="{{$product->id}}" {{$license->product->id == $product->id ? 'selected' : ''}}>
                    {{$product->name}}
                </option>
            @endforeach
        </select>
    </div>

    <div class="form-group mb-3">
        <label for="user_id">ادمین / نماینده</label>
        <select name="user_id" id="user_id" class="form-select mt-1" required>
            @foreach($users as $user)
                <option value="{{$user->id}}" {{$license->user->id == $user->id ? 'selected' : ''}}>
                    {{$user->name}}
                </option>
            @endforeach
        </select>
    </div>

    <div class="form-group mb-3">
        <label for="max_use">حداکثر استفاده</label>
        <input type="number" name="max_use" id="max_use" class="form-control mt-1"
               placeholder="این لایسنس چند بار قابلیت نصب دارد؟" min="1" max="2000" required
               value="{{$license->max_use}}">
    </div>

    <div class="row">
        <div class="col-lg-6">
            <div class="form-group mb-3">
                <label for="status">وضعیت</label>
                <select name="status" id="status" class="form-select mt-1">
                    <option value="1" {{$license->status ? 'selected' : ''}}>فعال</option>
                    <option value="0" {{!$license->status ? 'selected' : ''}}>غیر فعال</option>
                </select>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="form-group mb-3">
                <label for="pdate">
                    تاریخ انقضا
                    <i class="text-danger fa fa-warning ms-2" data-bs-toggle="tooltip" data-bs-placement="bottom"
                       title="در صورتی که لایسنس استفاده شده باشد و شما مقدار آن را خالی قرار دهید این لایسنس بدون محدودیت قابل استفاده میگردد!"></i>
                </label>
                <input type="text" placeholder="تاریخ انقضا" name="expires_at" autocomplete="off"
                       class="form-control mt-1 pdate" value="{{$license->expires_at}}">
            </div>
        </div>
    </div>
</form>

<script>
    $(".pdate").persianDatepicker({
        showGregorianDate: true,
        formatDate: "YYYY-0M-0D",
        observer: true,
    });

    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })
</script>
