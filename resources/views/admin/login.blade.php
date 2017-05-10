@extends('admin.empty')


@section('content')
    @parent
    
    <div style="text-align:center">
        <div style="width:600px;height:600px;margin:10px auto;margin-top:100px">
        
    
    <div class="span6" style="margin-left:0px;width:100%">
          <div class="widget-box">
            <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
              <h5>Login</h5>
            </div>
            <div class="widget-content nopadding">
              <form action="{{url('admin/loginPost')}}" method="post" class="form-horizontal">
                <input type="hidden" name="_token" value="{{csrf_token()}}"/>
                <div class="control-group">
                  <label class="control-label">Username :</label>
                  <div class="controls">
                    <input type="text" class="span11" placeholder="First name" name="email">
                  </div>
                </div>
                <div class="control-group">
                  <label class="control-label">Password :</label>
                  <div class="controls">
                    <input type="password" class="span11" placeholder="Last name" name="password">
                  </div>
                </div>
                <div class="form-actions">
                  <button type="submit" class="btn btn-success">Submit</button>
                </div>
                
                <div class="control-group">
                  <div class="controls" id="forgot-password-text">
                    Forgot password
                  </div>
                </div>
                @include('admin.global-message')
              </form>
            </div>
          </div>
    </div>
    
        </div>
    </div>
@endsection