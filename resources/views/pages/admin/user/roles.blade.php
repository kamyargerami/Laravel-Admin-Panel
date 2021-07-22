@extends('layouts.admin')

@section('title','دسترسی ها')

@section('content')
    <div class="card">
        <div class="card-header">
            <form>
                <div class="row">
                    <div class="col-md-3">
                        <input type="text" name="id" placeholder="ID" class="form-control"
                               value="{{request('id')}}">
                    </div>
                    <div class="col-md-3">
                        <input type="text" name="name" placeholder="نام نقش" class="form-control"
                               value="{{request('name')}}">
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-primary w-100">جستجو</button>
                    </div>
                    <div class="col-md-2">
                        <a href="{{route('admin.user.roles')}}" class="btn btn-warning w-100">
                            پاک کردن فرم
                        </a>
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-success w-100" data-bs-toggle="modal" data-bs-target="#addRoleModal"
                                type="button">
                            افزودن نقش
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div class="card-body">
            <table class="table table-bordered table-responsive-sm">
                <tr>
                    <th>#</th>
                    <th width="80%">نام نقش</th>
                    <th>عملیات</th>
                </tr>

                @foreach($roles as $role)
                    <tr>
                        <td>{{$role->id}}</td>
                        <td>{{__('roles.' . $role->name)}}</td>
                        <td>
                            <button class="btn btn-dark btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#defaultModal"
                                    data-path="{{ route('admin.user.permissions', $role->id) }}"
                                    data-title="مجوز ها" type="button"
                                    data-confirm-text="تنظیم مجوز ها">
                                تنظیم مجوز ها
                            </button>
                        </td>
                    </tr>
                @endforeach
            </table>

            @include('partials.paginate',['pages' => $roles])
        </div>
    </div>

    <div class="modal fade" id="addRoleModal">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">افزودن نقش</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{route('admin.user.add-role')}}" method="post">
                    @csrf
                    <div class="modal-body">
                        <input type="text" placeholder="نام نقش" name="name" class="form-control"
                               value="{{old('name')}}">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">کنسل</button>
                        <button type="submit" class="btn btn-primary">افزودن</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).on('change', '#select_all', function () {
            $('.permission').attr('checked', $(this).is(':checked'));
        });
    </script>
@endsection
