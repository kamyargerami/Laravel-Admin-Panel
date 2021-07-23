<form action="{{route('admin.product.update', $product->id)}}" id="dynamic-form" method="post">
    @csrf

    <div class="form-group">
        <label for="name">نام محصول</label>
        <input type="text" name="name" id="name" placeholder="نام محصول به انگلیسی" class="form-control ltr mt-2"
               value="{{$product->name}}">
    </div>
</form>
