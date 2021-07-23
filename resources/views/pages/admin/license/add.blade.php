<form action="{{route('admin.license.add')}}" id="dynamic-form" method="post">
    @csrf

    <div class="form-group mb-3">
        <label for="product_id">محصول</label>
        <select name="product_id" class="form-select mt-1" id="product_id" required>
            @foreach($products as $product)
                <option value="{{$product->id}}">
                    {{$product->name}}
                </option>
            @endforeach
        </select>
    </div>

    <div class="form-group mb-3">
        <label for="user_id">ادمین / نماینده</label>
        <select name="user_id" id="user_id" class="form-select mt-1" required>
            <option value="">خالی - بعدا متصل میکنم</option>
            @foreach($users as $user)
                <option value="{{$user->id}}" {{$user->id == 1 ? 'selected' : ''}}>
                    {{$user->name}}
                </option>
            @endforeach
        </select>
    </div>

    <div class="form-group mb-3">
        <label for="quantity">تعداد</label>
        <input type="number" name="quantity" id="quantity" class="form-control mt-1"
               placeholder="تعداد لایسنس برای ایجاد" required min="1" max="2000">
    </div>

    <div class="form-group mb-3">
        <label for="character_length">تعداد کاراکتر</label>
        <input type="number" name="character_length" id="character_length" class="form-control mt-1"
               placeholder="لایسنس ها دارای چند کاراکتر باشند؟" min="10" max="150" required>
    </div>

    <div class="form-group mb-3">
        <label for="max_use">حداکثر استفاده</label>
        <input type="number" name="max_use" id="max_use" class="form-control mt-1"
               placeholder="این لایسنس چند بار قابلیت نصب دارد؟" min="1" max="2000" required>
    </div>
</form>
