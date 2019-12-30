@extends('base::frontend.Template')


@section('page_title', 'Blog')
@section('body-class', '')

@section('meta')
@stop

@section('page_js')
@stop

@section('page_css')
@stop

@section('breadcrumbs')
    {!! breadcrumbs(['Home' => url('/'), 'Blog' => '/posts', 'View Post' => 'is_current']) !!}
@stop

@section('page-header')
    <span class="text-semibold">Blog</span>
@stop

@section('pre-content')
    <div class="page-section page-intro"><img class="img-responsive=" src="/images/Blog-hero.jpg" /></div>
@stop

@section('pre-content')
    <div class="cs-subheader">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="cs-subheader-text">
                        <h2>{{ $post->title }}</h2>

                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="section-content col-lg-9 col-md-9 col-sm-12 col-xs-12">
            <div class="content-area">
                @if($post->meta->featured_image)
                    <ul class="blog-detail-slider" style="margin-bottom:30px;">
                        <li>
                            <figure><img src="{{ url($post->meta->featured_image) }}" alt="" /></figure>
                        </li>
                    </ul>
                @endif
                <div class="cs-blog-post">
                    <div class="cs-thumb-post">
                        @if($post->user->profile->picture)

                            <div class="cs-media">
                                <figure>{!! show_profile_pic($post->user, 'img-responsive') !!}</figure>
                            </div>
                        @endif
                        <div class="cs-text">
                            <span>by <a href="#">{{ $post->user->username }}</a><br>{!! $post->publish_on->toDayDateTimeString() !!}</span>
                        </div>
                    </div>
                    <div class="cs-post-options">

                    </div>
                </div>
                <div class="cs-blog-detail-text">
                    {!! $post->content !!}
                </div>
                @if($post->tags->count() > 0)
                    <div class="cs-blog-tags">
                        <div class="cs-tags">
                            <label><i class="icon-tags"></i>Tags</label>
                            <ul>
                                @foreach($post->tags as $tag)
                                    <li><a href="{{ url('tag/'.$tag->slug.'/posts') }}">{{ $tag->name }}</a></li>
                                @endforeach
                            </ul>
                        </div>
                        <ul class="cs-social-media">

                        </ul>
                    </div>
                @endif
                    <div class="addthis_inline_share_toolbox"></div>
                <div class="cs-about-author">
                    @if($post->user->profile->picture)
                        <div class="cs-media">
                            <figure>{!! show_profile_pic($post->user, 100, '') !!}</figure>
                        </div>
                    @endif
                    <div class="cs-text">
                        <span>published by {{ $post->user->username }}</span>
                        <p>{!! nl2br($post->user->profile->bio) !!}</p>
                    </div>

                </div>

            </div>
        </div>
        <aside class="section-sidebar col-lg-3 col-md-3 col-sm-12 col-xs-12">

            <div class="widget widget-recent-posts">
                <h6>Recent Posts</h6>
                <ul>
                    @foreach($latestPosts as $post)
                        <li>

                            <div class="cs-media">
                                @if($post->meta->featured_image)
                                    <figure>
                                        <a href="{!! postLink($post) !!}"><img alt="" src="{{ url($post->meta->featured_image) }}"></a>
                                    </figure>
                                @endif
                            </div>
                            <div class="cs-text">
                                <a href="{!! postLink($post) !!}">{{ $post->title }}</a>
                                <span><i class="icon-clock5"></i>{{ $post->publish_on->diffForHumans() }}</span>
                            </div>
                        </li>
                    @endforeach
                </ul>
                <a href="{!! url('/posts') !!}" class="cs-view-blog">View all Blogs</a>
            </div>
            <div class="widget widget-tags">
                <h6>Tag Cloud</h6>
                @foreach($tags as $tag)
                    <a href="{!! url('/tag/'.$tag->slug.'/posts') !!}">{{ ucwords($tag->name) }}</a>
                @endforeach
            </div>
            <div class="widget widget-categories">
                <h6>Top Categores</h6>
                <ul>
                    @foreach($categories as $category)
                        <li>
                            <a href="{!! url('/category/'.$category->slug.'/posts') !!}">{{ ucwords($category->name) }} ( @if(isset($category->postCount->aggregate)){{$category->postCount->aggregate}}@else 0 @endif )</a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </aside>
    </div>


@stop


