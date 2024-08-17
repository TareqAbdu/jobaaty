@if ((string)$siteSetting->facebook_address !== '')
<a class="social-icons" href="{{ $siteSetting->facebook_address }}" target="_blank"><i class="fab fa-facebook" aria-hidden="true"></i></a>
@endif
@if ((string)$siteSetting->pinterest_address !== '')
<a class="social-icons" href="{{ $siteSetting->pinterest_address }}" target="_blank"><i class="fab fa-pinterest" aria-hidden="true"></i></a>
@endif
@if ((string)$siteSetting->twitter_address !== '')
<a class="social-icons" href="{{ $siteSetting->twitter_address }}" target="_blank"><i class="fab fa-twitter" aria-hidden="true"></i></a>
@endif
@if ((string)$siteSetting->instagram_address !== '')
<a class="social-icons" href="{{ $siteSetting->instagram_address }}" target="_blank"><i class="fab fa-instagram" aria-hidden="true"></i></a>
@endif
@if ((string)$siteSetting->linkedin_address !== '')
<a class="social-icons" href="{{ $siteSetting->linkedin_address }}" target="_blank"><i class="fab fa-linkedin" aria-hidden="true"></i></a>
@endif
@if ((string)$siteSetting->youtube_address !== '')
<a class="social-icons" href="{{ $siteSetting->youtube_address }}" target="_blank"><i class="fab fa-youtube" aria-hidden="true"></i></a>
@endif
@if ((string)$siteSetting->tumblr_address !== '')
<a class="social-icons" href="{{ $siteSetting->tumblr_address }}" target="_blank"><i class="fab fa-tumblr" aria-hidden="true"></i></a>
@endif
@if ((string)$siteSetting->flickr_address !== '')
<a class="social-icons" href="{{ $siteSetting->flickr_address }}" target="_blank"><i class="fab fa-flickr" aria-hidden="true"></i></a>
@endif