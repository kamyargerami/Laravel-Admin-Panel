<form action="{{route('admin.product.update', $product->id)}}" id="dynamic-form" method="post">
    @csrf

    <p>
        از این نام برای ترجمه عنوان استفاده خواهد شد، لذا نام انگلیسی محصول را بدون فاصله وارد نمایید. برای جدا سازی
        کلمات میتوانید از آندرلاین استفاده نمایید.
    </p>

    <div class="form-group">
        <label for="name">نام محصول</label>
        <input type="text" name="name" id="name" placeholder="نام محصول به انگلیسی" class="form-control ltr"
               value="{{$product->name}}">
    </div>
</form>
