<form action="{{route('admin.license.update', $license->id)}}" id="dynamic-form" method="post">
    @csrf

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
                    <option value="0">غیر فعال</option>
                </select>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="form-group mb-3">
                <label for="pdpGregorian">تاریخ انقضا</label>
                <input type="text" placeholder="تاریخ انقضا" name="expires_at" id="pdpGregorian"
                       class="form-control mt-1" value="{{$license->expires_at}}">
            </div>
        </div>
    </div>
</form>

<script>
    var pd = $("#pdpGregorian").persianDatepicker({
        showGregorianDate: true,
        formatDate: "YYYY-0M-0D",
        observer: true,
    });
</script>
