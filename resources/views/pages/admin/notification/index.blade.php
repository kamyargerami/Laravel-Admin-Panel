@extends('layouts.admin')

@section('title', 'نوتیفیکیشن های ارسال شده')

@section('content')
    <div class="card">
        <div class="card-header">
            نوتیفیکیشن های ارسال شده
        </div>
        <div class="card-body">
            <div class="table-responsive table-bordered">
                <table class="table">
                    <thead>
                    <tr>
                        <th>نوع</th>
                        <th>دریافت کننده</th>
                        <th>ایجاد</th>
                        <th>عملیات</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($notifications as $notification)
                        <tr>
                            <td>
                                @if($notification->type == 'App\Notifications\SMSNotification')
                                    پیامک
                                @elseif($notification->type == 'App\Notifications\EmailNotification')
                                    ایمیل
                                @else
                                    {{$notification->type}}
                                @endif
                            </td>
                            <td>{{$notification->notifiable ? $notification->notifiable->name : '--'}}</td>
                            <td>{{\Morilog\Jalali\Jalalian::fromDateTime($notification->created_at)}}</td>
                            <td>
                                @foreach($notification->data ?: [] as $key => $value)
                                    @if($value)
                                        <span class="bg-light p-1 font-12 radius-5 me-2">
                                            {{$key . ': ' . $value}}
                                        </span>
                                    @endif
                                @endforeach
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer">
            @include('partials.paginate',['pages' => $notifications])
        </div>
    </div>
@endsection
