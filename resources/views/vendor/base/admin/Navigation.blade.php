<!-- Main navbar -->
<div class="navbar navbar-inverse">
    <div class="navbar-header">
        <a class="navbar-brand" href="index.html"><img src="{!! url('/images/QheaderBase.png') !!}" alt=""></a>

        <ul class="nav navbar-nav pull-right visible-xs-block">
            <li><a data-toggle="collapse" data-target="#navbar-mobile"><i class="icon-tree5"></i></a></li>
        </ul>
    </div>

    <div class="navbar-collapse collapse" id="navbar-mobile">


        <ul class="nav navbar-nav navbar-right">
            @include('admin.partials.nav_alert_problems')
            @include('admin.partials.nav_alert_eventReminder')
            @include('tickets::partials.nav_alert')
            <li class="dropdown dropdown-user">
                <a class="dropdown-toggle" data-toggle="dropdown">
                    {!! show_profile_pic(Auth::user()) !!}
                <span>{!! Auth::user()->profile->first_name !!} {!! Auth::user()->profile->last_name !!}</span>
                    <i class="caret"></i>
                </a>

                <ul class="dropdown-menu dropdown-menu-right">
                    <li><a href="{!! url('members/profile') !!}"><i class="icon-user-plus"></i> My profile</a></li>
                    <li class="divider"></li>
                    <li><a href="{!! url('auth/logout') !!}"><i class="icon-switch2"></i> Logout</a></li>
                </ul>
            </li>
        </ul>
    </div>
</div>
<!-- /main navbar -->


<!-- Second navbar -->
<div class="navbar navbar-default" id="navbar-second">
    <ul class="nav navbar-nav no-border visible-xs-block">
        <li><a class="text-center collapsed" data-toggle="collapse" data-target="#navbar-second-toggle"><i class="icon-menu7"></i></a></li>
    </ul>

    <div class="navbar-collapse collapse" id="navbar-second-toggle">
        {!! Menu::show('Main Admin Menu') !!}

        <ul class="nav navbar-nav navbar-right">
            <li><a href="{{url('members/dashboard')}}"><i class="icon-rotate-ccw2"></i> Return To Members Area</a></li>


        </ul>
    </div>


</div>
<!-- /second navbar -->