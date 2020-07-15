<!-- footer logo section -->
<ul class="footer-logo">
  <li><img src="{{ asset('images/footer-logo-1.png') }}" alt="footer logo"/></li>
  <li><img src="{{ asset('images/footer-logo-2.png') }}" alt="footer logo"/></li>
  <li><img src="{{ asset('images/footer-logo-3.png') }}" alt="footer logo"/></li>
  <li><img src="{{ asset('images/footer-logo-4.png') }}" alt="footer logo"/></li>
  <li><img src="{{ asset('images/footer-logo-5.png') }}" alt="footer logo"/></li>
  <li><img src="{{ asset('images/footer-logo-6.png') }}" alt="footer logo"/></li>
  <li><img src="{{ asset('images/footer-logo-9.png') }}" alt="footer logo"/></li>
</ul>
<!-- end footer logo section -->

<footer class="footer">
  <div class="container">
    <div class="row">
        <div class="col-md-4 order-md-3 order-1">
        <ul class="payment-icon list-unstyled">
          <li><img src="{{ asset('images/pay-pal.png') }}" alt=""/></li>
          <li><img src="{{ asset('images/mastercard.png') }}" alt=""/></li>
          <li><img src="{{ asset('images/visa.png') }}" alt=""/></li>
          <li><img src="{{ asset('images/american-express.png') }}" alt=""/></li>
          <li><img src="{{ asset('images/discover.png') }}" alt=""/></li>
        </ul>
      </div>
        
      <div class="col-md-3 order-2">
        <h6>Shop with us</h6>
        <ul class="footer-links list-unstyled">
			@foreach(config('constants.PARENTCATEGORIES') as $parent_cat)
				<li><a href="{{ route('page.index', $parent_cat->slug).'/' }}">{{$parent_cat->name}}</a></li>
			@endforeach
        </ul>
      </div>
      <div class="col-md-5 order-2">
        <h6>Site Links</h6>
        <ul class="footer-links list-unstyled">
			@foreach(config('constants.SITELINKS') as $site_link)
				<li><a href="{{route('page.index', $site_link->slug).'/' }}">{{$site_link->title}}</a></li>
			@endforeach
        </ul>
		
        <ul class="social-links list-inline">
          <li><a href="{{config('constants.FACEBOOK_URL')}}"><span class="screen-reader-text">Facebook</span></a></li>
          <li><a href="{{config('constants.TWITTER_URL')}}"><span class="screen-reader-text">Twitter</span></a></li>
          <li><a href="{{config('constants.YOUTUBE_URL')}}"><span class="screen-reader-text">YouTube</span></a></li>
          
          <!-- <li><a href="http://plus.google.com"><span class="screen-reader-text">Google plus</span></a></li>
     <li><a href="http://instagram.com"><span class="screen-reader-text">Instagram</span></a></li>
     <li><a href="http://linkedin.com"><span class="screen-reader-text">linkedin</span></a></li>
     <li><a href="http://pinterest.com"><span class="screen-reader-text">Pinterest</span></a></li>-->
        </ul>
      </div>
      
      <div class="col-md-12 order-4">
        <hr/>
        <address class="copy">
        Copyright © {{date('Y')}} Fruit and Veg. All Rights Reserved
        </address>
        <ul class="privacy-link list-unstyled">
          <li><a href="{{route('sitemap').'/' }}">Sitemap</a></li>
          <li><a href="{{route('page.index', 'privacy-policy').'/' }}">Privacy Policy</a></li>
          <li><a href="{{route('page.index', 'terms-and-conditions').'/' }}">Terms and Conditions</a></li>
        </ul>
      </div>
    </div>
  </div>
</footer>