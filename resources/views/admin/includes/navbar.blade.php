<nav class="navbar bg-light navbar-expand-lg position-relative">
    <div class="container-fluid">
        <a class="navbar-brand" target="_blank" href="{{ url('/') }}">HRM</a>
        <div class="collapse navbar-collapse col-md-8" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <div class="col-md-1" style="margin-top: -22px;">
                    <a class="dropdown text-danger" data-bs-toggle="dropdown" aria-expanded="false">
                        <span class="badge text-bg-warning rounded-pill position-relative dropdown-toggle" style="top: 15px; left: 12px;">{{getNotificationsCount()}}</span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-bell" viewBox="0 0 16 16">
                            <path d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2zM8 1.918l-.797.161A4.002 4.002 0 0 0 4 6c0 .628-.134 2.197-.459 3.742-.16.767-.376 1.566-.663 2.258h10.244c-.287-.692-.502-1.49-.663-2.258C12.134 8.197 12 6.628 12 6a4.002 4.002 0 0 0-3.203-3.92L8 1.917zM14.22 12c.223.447.481.801.78 1H1c.299-.199.557-.553.78-1C2.68 10.2 3 6.88 3 6c0-2.42 1.72-4.44 4.005-4.901a1 1 0 1 1 1.99 0A5.002 5.002 0 0 1 13 6c0 .88.32 4.2 1.22 6z"/>
                        </svg>
                    </a>
                    @if(getNotificationsCount() != 0)
                        <ul class="dropdown-menu">
                            @foreach(getNotifications() as $notification)
                                <li class="dropdown-item card">
                                    <p>
                                        {{$notification->data['name']}}
                                    </p>
                                    <p>
                                        {{$notification->data['message']}}
                                    </p>
                                    <a href="{{ route('admin.mark-as-read',['id' => $notification->id]) }}" id="mark_as_read" data-id="{{$notification->id}}" class="btn btn-warning">
                                        Mark as read
                                    </a>
                                </li>
                            @endforeach
                            <button class="btn btn-light col-8" type="button" id="delete_all_notifications">Delete all notifications</button>
                        </ul>
                    @endif

                </div>
            </ul>
        </div>
        <div class="collapse navbar-collapse col-md-2">
            <ul class="navbar-nav me-auto col-md-2 mb-2 mb-lg-0">
                <div class="col-md-1" >
                    <a href="{{ route('admin.logout') }}" class="btn btn-light">Logout</a>
                </div>
            </ul>
        </div>
    </div>
</nav>
<hr/>
