@if(Auth::guard('company')->check())
<form action="{{route('job.seeker.list')}}" method="get">
    <div class="searchbar">
		<div class="srchbox">		
		<div class="input-group">
		  <input type="text"  name="search" id="empsearch" value="{{Request::get('search', '')}}" class="form-control" placeholder="{{__('Enter Skills or Job Seeker Details')}}" autocomplete="off" />
		  <span class="input-group-btn">
			<input type="submit" class="btn" value="{{__('Search Job Seeker')}}">
		  </span>
		</div>
		</div>
		
       
        
    </div>
</form>
@else
		
	
	<form class="banner-form" action="{{route('job.list')}}" method="get">
		<div class="row justify-content-center">
			<div class="col-md-4">
				<div class="form-group">
					<label for="jbsearch">{{__('Enter Skills or job title')}}:</label>
					<input type="text" name="search" id="jbsearch" value="{{Request::get('search', '')}}" class="form-control" placeholder="{{__('Enter Skills or job title')}}" autocomplete="off">
				</div>
			</div>
	
			<div class="col-md-4">
				<div class="form-group">
					<label for="functional_area_id">{{ __('Select Functional Area') }}:</label>
					{!! Form::select('functional_area_id[]', ['' => __('Select Functional Area')]+$functionalAreas, Request::get('functional_area_id', null), array('class'=>'form-control', 'id'=>'functional_area_id')) !!}
				</div>
			</div>
	
			<div class="col-md-4">
			
				<input type="submit" class="find-btn" value="{{__('Search Job Seeker')}}">

			</div>
		</div>
	</form>
    	
@endif