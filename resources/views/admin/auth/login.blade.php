<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="utf-8">
    
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    
    <title>{{ucwords(__('cp.jawhar'))}}</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link href="{{url('login/css/bootstrap.css')}}" rel="stylesheet">
    <link href="{{url('login/css/style.css')}}" rel="stylesheet">

    <link rel="shortcut icon" href="{{url('login/images/favicon.png')}}"/>
</head>
<body>

 <div class="login-wrapper">
      <div class="bg-pic">
        <img src="{{url('login/images/imgSign.png')}}" alt="" class="lazy">
      </div>
      <div class="login-container bg-white">
        <div class="logoLogin">
          <img src="{{url('login/images/logo.png')}}" alt="logo">
          <p class="titleLog">{{ucwords(__('cp.login'))}}</p>
          <p class="titleLog1">{{ucwords(__('cp.jawhar'))}} {{ucwords(__('cp.admincp'))}}</p>
          <form id="formLogin" class="formLogin" role="form" action="{{url(app()->getLocale().'/admin/login')}}" method="post">
            {{csrf_field()}}              
            <div class="form-group form-group-default">
              <label>{{ucwords(__('cp.email'))}}</label>
              <div class="controls">
                <input type="email" name="email" placeholder="{{ucwords(__('cp.email'))}}" class="form-control">
              </div>
            </div>
            <div class="form-group form-group-default">
              <label>{{ucwords(__('cp.password'))}}</label>
              <div class="controls">
                <input type="password" class="form-control" name="password" placeholder="{{ucwords(__('cp.password'))}}">
              </div>
            </div>
			<button class="btn btnSub" type="submit">{{ucwords(__('cp.login'))}}</button>
          </form>
          
           @if (count($errors) > 0)
            <div class="alert alert-danger">
                <strong>{{ucwords(__('cp.error'))}} ! </strong>{{ucwords(__('cp.wrongdataEntry'))}}<br>
                <ul class="list-unstyled">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

			
           <div class="pull-bottom pull-left">
                <p>Powerd By <a href="http://hexacit.com/" class="text-info"><img src="{{asset('website/images/hexaLogo.svg')}}" alt="Hexa Cit" /></a></p>
        </div>
      </div>
    </div>
    </body>
</html> 