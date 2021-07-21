@extends('layouts.admin')

@section('title', 'گزارشات')

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="table-responsive table-bordered">
                <table class="table">
                    <thead>
                    <tr>
                        <th>متن</th>
                        <th>کاربر</th>
                        <th>تاریخ</th>
                        <th>عملیات</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($logs as $log)
                        <tr>
                            <td>{{$log->compile()}}</td>
                            <td>{{$log->user ? $log->user->name : '--'}}</td>
                            <td>{{\Morilog\Jalali\Jalalian::fromDateTime($log->created_at)}}</td>
                            <td>
                                <a href="{{route('admin.log.details',$log->id)}}" class="btn btn-primary btn-sm"
                                   target="_blank">
                                    همه اطلاعات
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @if($logs->lastPage() > 1 and $logs->lastPage() <= $logs->currentPage())
            <div class="card-footer">
                @include('partials.paginate',['pages' => $logs])
            </div>
        @endif
    </div>
@endsection
