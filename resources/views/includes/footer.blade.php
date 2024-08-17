<section class="bg-footer">
    <div class="container">
        <div class="row">
            <div class="col-lg-4">
                <div class="footer-item mt-4 mt-lg-0 me-lg-5">
                    <h4 class="text-white mb-4">{{ $siteSetting->site_name }}</h4>
                    <p class="text-white-50">{{ __('It is a long established fact that a reader will be of a page reader will be of at its layout.') }}</p>
                    <p class="text-white mt-3">{{ __('Follow Us on:') }}</p>
                    <ul class="footer-social-menu list-inline mb-0">
                        <!-- Include Social Media Links -->
                        @include('includes.footer_social')
                    </ul>
                </div>
            </div><!--end col-->
            
            <div class="col-lg-2 col-6">
                <div class="footer-item mt-4 mt-lg-0">
                    <p class="fs-16 text-white mb-4">{{__('Quick Links')}}</p>
                    <ul class="list-unstyled footer-list mb-0">
                        <li><a href="{{ route('index') }}"><i class="mdi mdi-chevron-right"></i> {{__('Home')}}</a></li>
                        <li><a href="{{ route('contact.us') }}"><i class="mdi mdi-chevron-right"></i> {{__('Contact Us')}}</a></li>
                        <li class="postad"><a href="{{ route('post.job') }}"><i class="mdi mdi-chevron-right"></i> {{__('Post a Job')}}</a></li>
                        <li><a href="{{ route('faq') }}"><i class="mdi mdi-chevron-right"></i> {{__('FAQs')}}</a></li>
                        @foreach($show_in_footer_menu as $footer_menu)
                        @php
                        $cmsContent = App\CmsContent::getContentBySlug($footer_menu->page_slug);
                        @endphp
                        <li class="{{ Request::url() == route('cms', $footer_menu->page_slug) ? 'active' : '' }}">
                            <a href="{{ route('cms', $footer_menu->page_slug) }}">
                                <i class="mdi mdi-chevron-right"></i> {{ $cmsContent->page_title }}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div><!--end col-->

            <div class="col-lg-2 col-6">
                <div class="footer-item mt-4 mt-lg-0">
                    <p class="fs-16 text-white mb-4">{{__('Jobs By Functional Area')}}</p>
                    <ul class="list-unstyled footer-list mb-0">
                        @php
                        $functionalAreas = App\FunctionalArea::getUsingFunctionalAreas(10);
                        @endphp
                        @foreach($functionalAreas as $functionalArea)
                        <li><a href="{{ route('job.list', ['functional_area_id[]'=>$functionalArea->functional_area_id]) }}">
                            <i class="mdi mdi-chevron-right"></i> {{$functionalArea->functional_area}}
                        </a></li>
                        @endforeach
                    </ul>
                </div>
            </div><!--end col-->

            <div class="col-lg-2 col-6">
                <div class="footer-item mt-4 mt-lg-0">
                    <p class="fs-16 text-white mb-4">{{__('Jobs By Industry')}}</p>
                    <ul class="list-unstyled footer-list mb-0">
                        @php
                        $industries = App\Industry::getUsingIndustries(10);
                        @endphp
                        @foreach($industries as $industry)
                        <li><a href="{{ route('job.list', ['industry_id[]'=>$industry->industry_id]) }}">
                            <i class="mdi mdi-chevron-right"></i> {{$industry->industry}}
                        </a></li>
                        @endforeach
                    </ul>
                </div>
            </div><!--end col-->

            <div class="col-lg-2 col-6">
                <div class="footer-item mt-4 mt-lg-0">
                    <p class="fs-16 text-white mb-4">{{__('Contact Us')}}</p>
                    <div class="address text-white-50">{{ $siteSetting->site_street_address }}</div>
                    <div class="email text-white-50 mt-2"> 
                        <a href="mailto:{{ $siteSetting->mail_to_address }}" class="text-white-50">
                            {{ $siteSetting->mail_to_address }}
                        </a> 
                    </div>
                    <div class="phone text-white-50 mt-2"> 
                        <a href="tel:{{ $siteSetting->site_phone_primary }}" class="text-white-50">
                            {{ $siteSetting->site_phone_primary }}
                        </a>
                    </div>
                </div>
            </div><!--end col-->

        </div><!--end row-->
    </div><!--end container-->
</section>

<div class="footer-alt">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <p class="text-white-50 text-center mb-0">
                    2024 &copy; {{ $siteSetting->site_name }} - {{__('All Rights Reserved')}}.
                    {{__('Design by')}} <a href="https://themeforest.net/search/themesdesign" target="_blank" class="text-reset text-decoration-underline">Themesdesign</a>
                </p>
            </div><!--end col-->
        </div><!--end row-->
    </div><!--end container-->
</div><!--end footer-alt-->

