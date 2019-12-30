<!-- Footer Start -->
<footer id="footer" style="background:#19171a;padding:32px 0 0">
    <div class="cs-footer-widgets">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="widget widget-our-partners">
                        @if(!Auth::user()->hasRole(Settings::get('main_content_role')))<div class="widget-section-title">
                        <p style="text-align:center">
                        <a href="{!! url('/members/upgrade') !!}"><img class="img-responsive" alt="Upgrade at gauk auctions uk" height="120" src="{!! url('/images/UpgradeFooter-auctions.jpg') !!}" width="550" /></a>

                        </div>
                        @endif
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <div class="widget widget-about-us">
                        <div class="widget-section-title">
                            <h6 style="color:#fff !important">Fast Nav</h6>
                        </div>
                        <ul>
<li><a href="http://fortyanddeuce.com" target="_blank">Apparel</a></li>
<li><a href="http://gaukauctions.com/auctioneers">Auctioneers</a></li>
<li><a href="http://gaukmotors.co.uk"">GAUK Motors</a></li>
<li><a href="http://gaukauctions.com/government-auctions-uk">About GAUK Auctions</a></li>
<li><a href="http://gaukauctions.com/about">GAUK History</a></li>
<li><a href="http://gaukmedia.com" target="_blank">GAUK Media</a></li>
<li><a href="http://gaukmotors.co.uk/gauk-gen4-aggregation-software" target="_blank">GAUK Gen4 Software</a></li>
<li><a href="http://gaukauctions.com/pricing">Pricing</a></li>
<li><a href="http://company.gaukmedia.com">Terms & Knowledgebase</a></li>
<li><a href="http://http://company.gaukmedia.com/blog/2018/03/26/copyright">Copyright</a></li>
<li><a href="http://gaukauctions.com/contact-us">Contact Us</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <div class="widget widget-news-letter">
                        <div class="widget-section-title">
                            <h6 style="color:#fff !important">SIGN UP FOR THE GAUK eBLAST</h6>
                        </div>
                        <p>All the latest news from the auction world. Stay up to date - No Spam!. </p>
                        <div id="newsletter_subscribe_form" class="cs-form" style="color: #ffffff">
                            <div class="row" id="subscribe_error_wrap" style="display:none;">
                                <div class="col-md-12" id="subscribe_errors" style="color: #ffffff">
                                </div>
                            </div>
                            <form id="newsletter_subscribe" method="post" action="/newsletter/subscribe/gauk-auctions" class="email-octopus-form">
                                {!! csrf_field() !!}
                                <div class="input-holder">
                                    <input type="email" name="email" class="email-octopus-email-address" placeholder="Enter Valid Email Address" required>
                                    <label> <i class="icon-send2"></i>
                                        <input class="cs-bgcolor" type="submit" value="submit">
                                    </label>
                                </div>
                            </form>
                        </div>
                        <div class="cs-social-media">
                            <ul>
                                <li><a href="https://www.facebook.com/gaukAuctionsOnline" target="_blank" data-original-title="facebook"><i class="icon-facebook-f"></i></a></li>
                                <li><a href="https://twitter.com/GaukMotors" target="_blank"  data-original-title="twitter"><i class="icon-twitter4"></i></a></li>
                                <li><a href="https://www.linkedin.com/in/paul-tranter-54588721" target="_blank" data-original-title="linkedin"><i class="icon-linkedin22"></i></a></li>
                                <li><a href="https://plus.google.com/111328781104694290355" target="_blank" data-original-title="google"><i class="icon-google-plus"></i></a></li>
                                <li><a href="#" data-original-title="rss"><i class="icon-rss"></i></a></li>
                                <li><a href="https://www.youtube.com/channel/UCgCqc3AZezqpYeAc26p_0zw" target="_blank"  data-original-title="youtube"><i class="icon-youtube"></i></a></li>
                                <li><a href="https://drivetribe.com/t/eXa0LG7LSGORbrgFstsgdw?iid=H3IyoNx1TbyqB92M5RqeAA" target="_blank"  data-original-title=""><img class="foot-soc-img" src="{{url('images/drivetribe.png')}}"></a></li>
                                <li><a href="https://www.instagram.com/gaukmotors/" target="_blank"  data-original-title=""><img class="foot-soc-img" src="{{url('images/instagram.png')}}"></a></li>
                            </ul>
                        </div>
                        <img class="img-responsive mt-20" src="{{url('images/Footer_Icons.png')}}">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="cs-copyright" style="background:#141215; padding-top:37px; padding-bottom:37px;">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <div class="copyright-text">
                        <p>&copy; {{ date('Y') }} Proudly created by GAUK Media GAUK™ is an established Trade Mark. All GAUK images are digitally watermarked, the database is seeded, access logs are recorded and the structure is permanently archived. No part of this site shall be copied or reproduced in any way whatsoever without the express permission of GAUK Media in writing. Offenders WILL be pursued under Copyright law. GAUK is protected by plagiarism and copyright monitoring software. <a href="http://company.gaukmedia.com/blog/2018/03/26/copyright/" target="_blank" rel="noopener">More Info</a></p>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <div class="cs-back-to-top">

                        <a class="btn-to-top cs-bgcolor" href=""><i class="icon-keyboard_arrow_up"></i></a> </div>
                </div>
            </div>
        </div>
    </div>
</footer>
<!-- Footer End -->

    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-144702091-1"></script>
    <script>
     window.dataLayer = window.dataLayer || [];
     function gtag(){dataLayer.push(arguments);}
     gtag('js', new Date());
     gtag('config', 'UA-144702091-1');
    </script>
