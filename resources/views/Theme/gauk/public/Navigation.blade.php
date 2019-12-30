<header id="header" class="{{$NavClass or ''}} @if(isset($NavClass)) networkAdjust @endif">
    <div class="container">
        <div class="row">
            <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                <div class="cs-logo">
                    <div class="cs-media">
                        <figure><a href="{!! url('/') !!}"><img src="{{url('/images/GAUK-Auctions.png')}}" alt="" /></a></figure>
                    </div>
                </div>
            </div>
            <div class="col-lg-10 col-md-10 col-sm-12 col-xs-12">
                <div class="cs-main-nav pull-right">
                    <nav class="main-navigation">
                            {!! Menu::show('Main Public Menu') !!}

                            <li class="cs-user-option">
                                <div class="cs-login">

                                    <!-- Modal -->
                                    @include(Theme::includeFile('Modal-Register','public'))
                                    @include(Theme::includeFile('Modal-Login','public'))
                                    @include(Theme::includeFile('Modal-Forgot','public'))



                                </div>

                            </li>
                        </ul>
                    </nav>
                    <div class="cs-user-option hidden-lg visible-sm visible-xs">
                        <div class="cs-login">
                            <!-- Modal -->
                            @include(Theme::includeFile('Modal-Register-Sm','public'))
                            @include(Theme::includeFile('Modal-Login-Sm','public'))
                            @include(Theme::includeFile('Modal-Forgot-Sm','public'))


                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>

</header>