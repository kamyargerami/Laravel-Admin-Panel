<div class="text-center">
    <p class="mt-2">
        موارد یافت شده: {{$pages->total()}}
    </p>

    <div class="d-flex justify-content-center mt-3">
        {{$pages->appends(request()->all())->onEachSide(0)->links()}}
    </div>
</div>
