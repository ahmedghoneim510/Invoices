@can('الاشعارات')
<div class="dropdown nav-item main-header-notification">
    <a class="new nav-link" href="#">
        <svg xmlns="http://www.w3.org/2000/svg" class="header-icon-svgs" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-bell"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg><span class=" pulse"></span></a>
    <div class="dropdown-menu">
        <div class="menu-header-content bg-primary text-right">
            <div class="d-flex">
                <h6 class="dropdown-title mb-1 tx-15 text-white font-weight-semibold">الاشعارات</h6>
                <span class="badge badge-pill badge-warning mr-auto my-auto float-left">تعين قراءه الكل</span>
            </div>
            <p id="notification_count" class="dropdown-title-text subtext mb-0 text-white op-6 pb-0 tx-12 ">عدد الاشعارات الغير مقروء {{$count}}</p>
        </div>
        <div class="main-notification-list Notification-scroll" id="unreadnotification">
            @foreach($notifications as $notification)
            <a class="d-flex p-3 border-bottom  "  href="{{route('InvoicesDetails',$notification['data']['id'])}}?notification_id={{$notification->id}}">
                <div class="notifyimg bg-pink">
                    <i class="la la-file-alt text-white"></i>
                </div>
                <div class="mr-3">
                    <h5 class="notification-label mb-1  " @if($notification->unread()) style="font-size:bold" @endif >{{ $notification['data']['title'] }} {{$notification['data']['user']}}</h5>
                    <div class="notification-subtext">{{$notification->created_at->longAbsoluteDiffForHumans()}}</div>
                </div>
                <div class="mr-auto" >
                    <i class="las la-angle-left text-left text-muted"></i>
                </div>
            </a>
            @endforeach
        </div>
        <div class="dropdown-footer">
            <a href="">VIEW ALL</a>
        </div>
    </div>
</div>
@endcan
