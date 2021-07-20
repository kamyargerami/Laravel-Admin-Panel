<div class="scrollable-y modal-height">
    @if(!count($logs))
        <p class="text-center text-muted">لاگی ثبت نشده است</p>
    @endif

    @foreach($logs as $log)
        <div class="bg-light p-2 border mb-3">
            <p>
                <a href="{{route('admin.log.details',$log->id)}}" class="text-dark" target="_blank">
                    {{$log->compile()}}
                </a>
            </p>

            <div class="row">
                <div class="col-6">
                    <a href="{{route('admin.user.list',['id' => $log->user_id])}}" target="_blank"
                       class="font-12 text-muted mb-0">
                        {{$log->user->name ?? ''}}
                    </a>
                </div>
                <div class="col-6">
                    <p class="font-12 mb-0 text-end">
                        {{\Morilog\Jalali\Jalalian::fromDateTime($log->created_at)}}
                    </p>
                </div>
            </div>
        </div>
    @endforeach
</div>
