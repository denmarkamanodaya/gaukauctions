<?php
$pageRoute = Auth::check() ? 'members' : '';
?>

@foreach($latestPosts as $post)
    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">

        <div class="col-md-12 latestPostCard">
            <a href="{!! postLink($post, $pageRoute) !!}">
            <div class="postCoverImage" style="background-image: url({{$post->meta->featured_image}});"></div>
            </a>
            <div class="col-md-12 postDetails mt-10">
                <h3><a href="{!! postLink($post, $pageRoute) !!}">{{$post->title}}</a></h3>

            <div class="summary">{!! $post->summary !!}</div>
            </div>
            <div class="vehicleDetailWrap_2">
                <a class="gavelBoxFavourite cs-color" href="{!! postLink($post, $pageRoute) !!}"><i class="far fa-calendar-alt"></i>{{$post->publish_on->toFormattedDateString()}}</a>

                <a style="float:right" class="View-btn" href="{!! postLink($post, $pageRoute) !!}">Read More <i class="far fa-angle-double-right"></i></a>

            </div>


        </div>

    </div>



@endforeach
