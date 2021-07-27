<select name="order_by" class="form-control">
    <option value="">-- مرتب سازی بر اساس --</option>
    @foreach($order_by as $column)
        @foreach(['asc','desc'] as $way)
            <option
                value="{{$column . '-' . $way}}" {{request('order_by') == $column . '-' . $way ? 'selected' : ''}}>
                {{__('order-by.' . $column) . ' ' . ($way == 'asc' ? 'صعودی' : 'نزولی')}}
            </option>
        @endforeach
    @endforeach
</select>
