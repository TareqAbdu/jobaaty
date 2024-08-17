<ul class="messages message{{$seeker->id}}">
  @if(session('success'))
      <div class="alert alert-success">
          {{ session('success') }}
      </div>
  @endif

  @if(null !== ($messages))
      <?php foreach($messages as $msg){ ?>
          <li class="<?php if($msg->type=='message'){?>friend-message<?php }else{?>my-message<?php }?> clearfix tab{{$seeker->id}}">
              <figure class="profile-picture"><?php if($msg->type=='message'){?> {{$seeker->printUserImage(100, 100)}} <?php }else{?>{{$company->printCompanyImage()}} <?php }?></figure>
              @if($msg->type!='meeting')
              <div class="message">
                {{$msg->message}}
              </div>
              @else
              <div class="message"> 
                {{$msg->meeting_url}}
              </div>
              @endif
                  <div class="time"><i class="fa fa-clock-o"></i>{{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $msg->updated_at)->diffForHumans() }}</div>
              </div>
          </li>
      <?php } ?>
  @endif
</ul>

<div class="chat-form" type="post">
  <form class="form-inline">
      @csrf
      <div class="form-group">
          <div class="input-wrap">
              <input type="hidden" name="seeker_id" value="{{$seeker->id}}">
              <textarea class="form-control" name="message" placeholder="Type Your Message here.."></textarea>
              <div class="input-group-prepend">
                  <button type="submit" class="input-group-text" id="inputGroupPrepend3"><i class="fas fa-paper-plane" aria-hidden="true"></i></button>
                  <button id="openPopupBtn" class="input-group-text"><i class="fas fa-video" aria-hidden="true"></i></button>

              </div>
          </div>
      </div>
  </form>
</div>
<!-- زر "إنشاء اجتماع" -->

<!-- النافذة المنبثقة -->
<div id="scheduleMeetingModal" class="modal">
    <div class="modal-content">
        <span class="close-btn">&times;</span>
        <h5>جدولة اجتماع</h5>
        <form id="schedule-meeting-form" method="POST" action="{{ route('company.create-meeting') }}">
            @csrf
            <div class="form-group">
                <label for="meeting_time">حدد وقت وتاريخ الاجتماع:</label>
                <input type="datetime-local" id="scheduled_at" name="scheduled_at" required class="form-control">
            </div>
            <div class="form-group">
                <label for="meeting_topic">موضوع الاجتماع:</label>
                <input type="text" id="meeting_topic" name="meeting_topic" required class="form-control">
            </div>
            <input type="hidden" name="seeker_id" value="{{$seeker->id}}">
            <button type="submit" class="btn btn-primary">جدولة الاجتماع</button>
        </form>
    </div>
</div>

<script>
  $(document).ready(function(){
      if ($(".form-inline").length > 0) {
          $(".form-inline").validate({
              validateHiddenInputs: true,
              ignore: "",

              rules: {
                  message: {
                      required: true,
                      maxlength: 5000
                  },
              },
              messages: {
                  message: {
                      required: "Message is required",
                  }
              },
              submitHandler: function(form) {
                  $.ajaxSetup({
                      headers: {
                          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                      }
                  });
                  $.ajax({
                      url: "{{route('company.submit-message')}}",
                      type: "POST",
                      data: $('.form-inline').serialize(),
                      success: function(res) {
                          $(".form-inline").trigger("reset");
                          $('.messages').html('');
                          $('.messages').html(res);
                          $(".messages").scrollTop(100000000000000);
                          $('.messages').off('scroll');
                      }
                  });
              }
          })
      }
  });

  clearInterval(chat_interval);
  var chat_interval = setInterval(function() {
      $.ajax({
          type: 'get',
          dataType: 'json',
          url: "{{route('append-only-message')}}",
          data: {
              '_token': $('input[name=_token]').val(),
              'seeker_id': "{{$seeker->id}}",
          },
          success: function(res) {
              $('.message' + res.seeker_id).html(res.html_data);
          }
      });
  }, 5000);

  // الحصول على عناصر HTML
var modal = document.getElementById("scheduleMeetingModal");
var openBtn = document.getElementById("openPopupBtn");
var closeBtn = document.getElementsByClassName("close-btn")[0];

// فتح النافذة عند الضغط على الزر
openBtn.onclick = function() {
    modal.style.display = "block";
}

// إغلاق النافذة عند الضغط على زر الإغلاق
closeBtn.onclick = function() {
    modal.style.display = "none";
}

// إغلاق النافذة عند الضغط خارجها
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}
</script>
<style>
  /* تنسيق الخلفية للنافذة المنبثقة */
.modal {
    display: none;
    position: fixed;
    z-index: 1;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0, 0, 0, 0.5);
}

/* تنسيق محتوى النافذة المنبثقة */
.modal-content {
    background-color: #fff;
    margin: 15% auto;
    padding: 20px;
    border: 1px solid #888;
    width: 80%;
    max-width: 500px;
    border-radius: 8px;
}

/* تنسيق زر الإغلاق */
.close-btn {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
}

.close-btn:hover,
.close-btn:focus {
    color: black;
    text-decoration: none;
    cursor: pointer;
}
  </style>
