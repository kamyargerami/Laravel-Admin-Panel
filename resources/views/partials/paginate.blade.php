<div class="text-center">
    <p class="mb-3">
        موارد یافت شده: {{$pages->total()}}
    </p>

    <div class="d-flex justify-content-center">
        {{$pages->appends(request()->all())->links()}}
    </div>
</div>
