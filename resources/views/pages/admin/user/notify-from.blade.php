<div class="modal fade" id="notifyModal">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">ارسال پیام به کاربران</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{route('admin.user.send-notification')}}" method="post">
                @csrf

                @foreach(request()->all() as $key => $value)
                    <input type="hidden" name="{{$key}}" value="{{$value}}">
                @endforeach

                <div class="modal-body">
                    <textarea class="form-control mt-5" rows="5" name="text"
                              placeholder="متن مورد نظر برای ارسال به کاربران">{{old('text')}}</textarea>

                    <div class="checkbox mt-5">
                        روش های ارسال:
                        <label class="me-2">
                            <input type="checkbox" value="sms" name="methods[]" class="me-2"
                                   @if(in_array('sms', old('methods',[]))) checked @endif>
                            SMS
                        </label>
                        <label class="me-2">
                            <input type="checkbox" value="email" name="methods[]" class="me-2"
                                   @if(in_array('email', old('methods',[]))) checked @endif>ایمیل
                        </label>
                    </div>

                    <div class="p-3">
                        <input type="text" name="subject" placeholder="موضوع ایمیل" class="form-control mb-4"
                               value="{{old('subject')}}">
                        <input type="text" name="button_text" placeholder="متن دکمه ایمیل" class="form-control mb-4"
                               value="{{old('button_text')}}">
                        <input type="text" name="button_link" placeholder="لینک دکمه ایمیل" class="form-control mb-4"
                               value="{{old('button_link')}}">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">بستن</button>
                    <button type="submit" class="btn btn-primary">ارسال</button>
                </div>
            </form>
        </div>
    </div>
</div>
