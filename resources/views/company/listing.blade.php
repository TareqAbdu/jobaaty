@extends('layouts.app')
@section('content') 
<!-- Header start --> 
@include('includes.header') 
<!-- Header end --> 

<div class="pageSearch">
<div class="container">



      <div class="row">
        <div class="col-lg-7">
        <h3 class="mb-1">{{__('Browse Companies')}}.</h3>	
        <h5 class="mb-3">{{__('Get hired in most high rated companies')}}.</h5>

        <section id="joblisting-header" role="search" class="searchform">
        	
            <form id="top-search" method="GET" action="{{route('company.listing')}}">                                 
            <div class="input-group">        
            <input type="text" name="search" value="{{Request::get('search', '')}}" class="form-control" placeholder="{{__('keywords e.g. "Google"')}}" />        
            <button type="submit" id="submit-form-top" class="btn"><i class="fa fa-search" aria-hidden="true"></i> {{__('Search Company')}}</button>
            </div> 
            </form>
        </section>  
        </div>
      </div>



</div></div>






<section class="section">
    <div class="container">
        <div class="row align-items-center mb-4">
            <div class="col-lg-8">
                <div class="mb-3 mb-lg-0">
                    <h6 class="fs-16 mb-0">{{ __('Showing Companies') }} : {{ $companies->firstItem() }} - {{ $companies->lastItem() }} {{ __('of') }} {{ $companies->total() }} {{ __('results') }}</h6>
                </div>
            </div><!--end col-->
        </div><!--end row-->

        <div class="row">
            @if($companies->isEmpty())
                <p>No active and verified companies found.</p>
            @else
                @foreach($companies as $company)
                <div class="col-lg-4 col-md-6">
                    <div class="card text-center mb-4">
                        <div class="card-body px-4 py-5">
                            <div class="featured-label">
                                <span class="featured">{{ $company->rating }} <i class="mdi mdi-star-outline"></i></span>
                            </div>
                            <div class="comimg">
                                {!! $company->printCompanyImage(200,200,'img-fluid rounded-3') !!}

                            </div>
                            <div class="mt-4">
                                <a href="{{ route('company.detail', $company->slug) }}" class="primary-link"><h6 class="fs-18 mb-2">{{ $company->name }}</h6></a>
                                <p class="text-muted mb-4">{{ $company->getCity('city') }}</p>

                                <a href="{{ route('company.detail', $company->slug) }}" class="btn btn-primary">{{ $company->countNumJobs('company_id', $company->id) }} {{ __('Opening Jobs') }}</a>
                            </div>
                        </div>
                    </div>
                </div><!--end col-->
                @endforeach
            @endif
        </div><!--end row-->

        <div class="row">
            <div class="col-lg-12 mt-5">
                <nav aria-label="Page navigation example">
                    <ul class="pagination job-pagination mb-0 justify-content-center">
                        {{ $companies->appends(request()->query())->links() }}
                    </ul>
                </nav>
            </div><!--end col-->
        </div><!-- end row -->
    </div><!--end container-->
</section>


@include('includes.footer')
@endsection
@push('styles')
<style type="text/css">
    .formrow iframe {
        height: 78px;
    }
</style>
@endpush
@push('scripts') 
<script type="text/javascript">
    $(document).ready(function () {
        $(document).on('click', '#send_company_message', function () {
            var postData = $('#send-company-message-form').serialize();
            $.ajax({
                type: 'POST',
                url: "{{ route('contact.company.message.send') }}",
                data: postData,
                //dataType: 'json',
                success: function (data)
                {
                    response = JSON.parse(data);
                    var res = response.success;
                    if (res == 'success')
                    {
                        var errorString = '<div role="alert" class="alert alert-success">' + response.message + '</div>';
                        $('#alert_messages').html(errorString);
                        $('#send-company-message-form').hide('slow');
                        $(document).scrollTo('.alert', 2000);
                    } else
                    {
                        var errorString = '<div class="alert alert-danger" role="alert"><ul>';
                        response = JSON.parse(data);
                        $.each(response, function (index, value)
                        {
                            errorString += '<li>' + value + '</li>';
                        });
                        errorString += '</ul></div>';
                        $('#alert_messages').html(errorString);
                        $(document).scrollTo('.alert', 2000);
                    }
                },
            });
        });
    });
</script> 
@endpush