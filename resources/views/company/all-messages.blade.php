@extends('layouts.app')

@section('content')
<!-- Header start -->
@include('includes.header')
<!-- Header end -->
<!-- Inner Page Title start -->
@include('includes.inner_page_title', ['page_title'=>__('Company Messages')])

<div class="container-fluid p-0 message-wrapper d-flex">
    <!-- Sidebar with user list -->
    <div class="sideleft col-md-4 p-0 d-flex flex-column">
        <div class="card h-100">
            <div class="card-header">
                <h4>{{ __('Messages') }}</h4>
            </div>
            <div class="list-group list-group-flush message-history flex-grow-1 overflow-auto">
                @if(null !== ($seekers))
                    @foreach($seekers as $seeker)
                        <a href="javascript:;" 
                           class="list-group-item list-group-item-action message-grid" 
                           id="adactive{{ $seeker->id }}" 
                           data-gift="{{ $seeker->id }}" 
                           onclick="show_messages({{ $seeker->id }})">
                            <div class="d-flex w-100 align-items-center">
                                <div class="image">
                                    {!! $seeker->printUserImage(50, 50) !!}
                                </div>
                                <div class="ml-3 user-name">
                                    <h5>{{ $seeker->name }}</h5>
                                    <small class="count-messages">{{ $seeker->countMessages(Auth::guard('company')->user()->id) }} {{ __('Messages') }}</small>
                                </div>
                            </div>
                        </a>
                    @endforeach
                @endif
            </div>
        </div>
    </div>

    <!-- Message content -->
    <div class="content-area col-md-8 p-0 d-flex flex-column">
        <div class="card h-100">
            <div class="card-header">
                <h4>{{ __('Conversation') }}</h4>
            </div>
            <div class="card-body message-content flex-grow-1 overflow-auto">
                <div id="append_messages" class="messages"></div>
            </div>
        </div>
    </div>
</div>

@endsection


@push('scripts')
<script type="text/javascript">
function show_messages(id) {
    $.ajax({
        type: "GET",
        url: "{{ route('company-change-message-status') }}",
        data: { 'sender_id': id },
        success: function() {
            $.ajax({
                type: 'GET',
                url: "{{ route('append-message') }}",
                data: { '_token': $('input[name=_token]').val(), 'seeker_id': id },
                success: function(res) {
                    $('#append_messages').html(res);
                    $(".messages").scrollTop($(".messages")[0].scrollHeight);
                    $('.message-grid').removeClass('active');
                    $("#adactive" + id).addClass('active');
                }
            });
        }
    });
}
</script>
@endpush
