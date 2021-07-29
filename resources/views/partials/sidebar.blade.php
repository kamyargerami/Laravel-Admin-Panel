<a id="show-sidebar" class="btn btn-sm btn-dark" href="#">
    <i class="fa fa-times"></i>
</a>

<nav id="sidebar" class="sidebar-wrapper">
    <div class="sidebar-content">
        <div class="sidebar-brand">
            <a href="/">
                <img src="{{asset('images/logo-light.png')}}" class="img-fluid me-2" width="35px">
                <span class="font-22 text-white">آرسام سافت</span>
            </a>
            <div id="close-sidebar">
                <i class="fa fa-times"></i>
            </div>
        </div>

        <div class="sidebar-menu">
            <ul>
                @can('admin.license.list')
                    <li>
                        <a href="{{route('admin.license.list')}}">
                            <i class="fa fa-copyright"></i>
                            <span>لایسنس</span>
                        </a>
                    </li>
                @endcan
                @can('admin.product.list')
                    <li>
                        <a href="{{route('admin.product.list')}}">
                            <i class="fa fa-product-hunt"></i>
                            <span>محصولات</span>
                        </a>
                    </li>
                @endcan
                @can('admin.user.list')
                    <li>
                        <a href="{{route('admin.user.list')}}">
                            <i class="fa fa-users"></i>
                            <span>کاربران</span>
                        </a>
                    </li>
                @endcan
                @can('admin.log.index')
                    <li>
                        <a href="{{route('admin.log.index')}}">
                            <i class="fa fa-list"></i>
                            <span>گزارشات</span>
                        </a>
                    </li>
                @endcan
                @can('admin.user.roles')
                    <li>
                        <a href="{{route('admin.user.roles')}}">
                            <i class="fa fa-user-secret"></i>
                            <span>نقش های کاربری</span>
                        </a>
                    </li>
                @endcan
                @can('admin.notification.index')
                    <li>
                        <a href="{{route('admin.notification.index')}}">
                            <i class="fa fa-envelope"></i>
                            <span>نوتیفیکیشن</span>
                        </a>
                    </li>
                @endcan
            </ul>
        </div>
    </div>

    <div class="sidebar-footer">
        <a href="">
            <i class="fa fa-home"></i>
        </a>
        <a href="{{route('logout')}}">
            <i class="fa fa-power-off"></i>
        </a>
    </div>
</nav>
