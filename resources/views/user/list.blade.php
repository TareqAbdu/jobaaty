@extends('layouts.app')
@section('content') 
<!-- Header start --> 
@include('includes.header') 
<!-- Header end --> 


@include('flash::message')
        <form action="{{route('job.seeker.list')}}" method="get">
            <!-- Page Title start -->
            <div class="pageSearch">
				<div class="container">
                    <div class="row">                        
                        <div class="col-lg-8">
                            <div class="searchform">
                                <div class="row">
                                    <div class="col-md-5">
                                        <input type="text" name="search" value="{{Request::get('search', '')}}" class="form-control" placeholder="{{__('Enter Skills or job seeker details')}}" />
                                    </div>
                                    <div class="col-md-5"> {!! Form::select('functional_area_id[]', ['' => __('Select Functional Area')]+$functionalAreas, Request::get('functional_area_id', null), array('class'=>'form-control', 'id'=>'functional_area_id')) !!} </div>

                                    <div class="col-md-2">
                                        <button type="submit" class="btn"><i class="fa fa-search" aria-hidden="true"></i></button>
                                    </div>
                                </div>
                            </div>


                            @if(Auth::guard('company')->check())
                            <a href="{{ route('post.job') }}" class="btn btn-yellow mt-3"><i class="fa fa-file-text" aria-hidden="true"></i> {{__('Post Job')}}</a>
                            @else
                            <a href="{{url('my-profile#cvs')}}" class="btn btn-yellow mt-3"><i class="fa fa-file-text" aria-hidden="true"></i> {{__('Upload Your Resume')}}</a>
                            @endif


                        </div>
                    </div>
				</div>
            </div>
            <!-- Page Title end -->
        </form>



<div class="listpgWraper">
    <div class="container">
        
        <form action="{{route('job.seeker.list')}}" method="get">
            <!-- Search Result and sidebar start -->
            <div class="row"> @include('includes.job_seeker_list_side_bar')                
                <div class="col-lg-9"> 
                    <!-- Search List -->
                    <section class="section">
                        <div class="container">
                          
                            <div class="row align-items-center">
                                <div class="col-lg-8 col-md-7">
                                    <div>
                                        <h6 class="fs-16 mb-0"> Showing {{ $jobSeekers->firstItem() }} – {{ $jobSeekers->lastItem() }} of {{ $jobSeekers->total() }} results </h6>
                                    </div>
                                </div><!--end col-->
                    
                            
                            </div><!--end row-->
                    
                            <div class="candidate-list">
                                <div class="row">
                                    @if(isset($jobSeekers) && count($jobSeekers))
                                        @foreach($jobSeekers as $jobSeeker)
                                        <div class="col-lg-4 col-md-6">
                                            <div class="candidate-grid-box bookmark-post card mt-4">
                                                <div class="card-body p-4">
                                                    <div class="d-flex mb-4">
                                                        <div class="flex-shrink-0 position-relative circle-image">
                                                            {!! $jobSeeker->printUserImage(50, 50) !!}
                                                        </div>
                                                        <div class="ms-3">
                                                            <a href="{{ route('user.profile', $jobSeeker->id) }}" class="primary-link"><h5 class="fs-17">{{ $jobSeeker->getName() }}</h5></a>
                                                            
                                                            <p>{{ $jobSeeker->getLocation() }}</p>
                                                        </div>
                                                    </div>
                                                    <p class="text-muted">{{ \Illuminate\Support\Str::limit($jobSeeker->getProfileSummary('summary'), 150, '...') }}</p>
                                                    <div class="mt-3">
                                                        <a href="{{ route('user.profile', $jobSeeker->id) }}" class="btn btn-soft-primary btn-hover w-100 mt-2"><i class="fas fas-eye"></i> {{ __('View Profile') }}</a>
                                                    </div>
                                                </div>
                                            </div> <!--end card-->
                                        </div><!--end col-->
                                        @endforeach
                                    @else
                                        <p>No active and verified job seekers found.</p>
                                    @endif
                                </div><!--end row-->
                            </div><!--end candidate-list-->
                    
                            <div class="row mt-5 pt-2">
                                <div class="col-lg-12">
                                    <nav aria-label="Page navigation example">
                                        <ul class="pagination job-pagination mb-0 justify-content-center">
                                            {{ $jobSeekers->appends(request()->query())->links() }}
                                        </ul>
                                    </nav>
                                </div><!--end col-->
                            </div><!-- end row -->
                    
                        </div><!--end container-->
                    </section>
                    

                    <!-- Pagination Start -->
                    <div class="pagiWrap">
                        <div class="row">
                            <div class="col-md-5">
                                <div class="showreslt">
                                    {{__('Showing Pages')}} : {{ $jobSeekers->firstItem() }} - {{ $jobSeekers->lastItem() }} {{__('Total')}} {{ $jobSeekers->total() }}
                                </div>
                            </div>
                            <div class="col-md-7 text-right">
                                @if(isset($jobSeekers) && count($jobSeekers))
                                {{ $jobSeekers->appends(request()->query())->links() }}
                                @endif
                            </div>
                        </div>
                    </div>
                    <!-- Pagination end --> 
                    <div class=""><br />{!! $siteSetting->listing_page_horizontal_ad !!}</div>

                </div>
        </form>
    </div>
</div>
@include('includes.footer')
@endsection
@push('styles')
<style type="text/css">
    .searchList li .jobimg {
        min-height: 80px;
    }
    .hide_vm_ul{
        height:100px;
        overflow:hidden;
    }
    .hide_vm{
        display:none !important;
    }
    .view_more{
        cursor:pointer;
    }
</style>
@endpush
@push('scripts') 
<script>
    $(document).ready(function ($) {
        $("form").submit(function () {
            $(this).find(":input").filter(function () {
                return !this.value;
            }).attr("disabled", "disabled");
            return true;
        });
        $("form").find(":input").prop("disabled", false);

        $(".view_more_ul").each(function () {
            if ($(this).height() > 100)
            {
                $(this).addClass('hide_vm_ul');
                $(this).next().removeClass('hide_vm');
            }
        });
        $('.view_more').on('click', function (e) {
            e.preventDefault();
            $(this).prev().removeClass('hide_vm_ul');
            $(this).addClass('hide_vm');
        });

    });
</script>
@include('includes.country_state_city_js')
@endpush