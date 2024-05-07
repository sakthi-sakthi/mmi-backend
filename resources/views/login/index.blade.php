<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  {{-- <link rel="icon" sizes="48x48" href="{{App\Models\File::find(App\Models\Option::where('key','=','favicon')->first()->value)->getMedia()->first()->getUrl('thumb')}}" type="image/x-icon" /> --}}
  <link rel="icon" href="{{asset('admin')}}/img/embelem.png" type="image/x-icon" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Welcome to Missionaries of Mary Immaculate | {{ __('main.Login') }}</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link rel="stylesheet" href="{{asset('admin')}}/plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="{{asset('admin')}}/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <link rel="stylesheet" href="{{asset('admin')}}/dist/css/adminlte.min.css">
  <style>
    .wrapper {
    width: 420px;
    /* background: transparent; */
    border:2px solid rgba(255, 255, 255, .2);
    backdrop-filter:blur(20px);
    box-shadow: 0 0 10px rgba(0 , 0 , 0 , .2);
    color: #fff;
    border-radius: 10px;
    padding: 30px 40px;

}
.body{
  font-family: "Comic Sans MS", cursive;
  /* background:url("{{asset('admin')}}/img/2.webp") no-repeat center center fixed; */
  background-color: #172440 !important;
  background-size: cover;
}
/* .body::after{
  content: "";
    position: absolute;
    top: 0;
    left: 0;
    z-index: -1;
    width: 100%;
    height: 100%;
    background: rgb(18 17 17 / 38%);

} */
  </style>
</head>
<body class="hold-transition login-page body" >
  <div class="register-logo">
    <img src="{{asset('admin')}}/img/embelem.jpg" style="width: 160px;" alt="logo"><br>
    <center><h1 style="color:black
     ; font-weight: 700;
   font-family: serif;"><b style="color: #fee082"> ADMIN LOGIN</b><br>
   <b style="color:white ">WELCOME TO MISSIONARIES OF MARY<br/> IMMACULATE </b>
   </h1></center>
  </div>
<div class="login-box">
  <div class="card" >
    <div class="card-body login-card-body wrapper">
      <p class="login-box-msg" style="color: rgb(17 38 134)">{{ __('main.Sign in to start your session') }}</p>
        @if ($errors->any()) <div class="alert alert-danger">{{$errors->first()}}</div> @endif
      <form action="{{route('login.check')}}" method="post">
        @csrf
        <div class="input-group mb-3">
          <input type="email" class="form-control" placeholder="{{ __('main.E-mail') }}" name="email" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" class="form-control" placeholder="{{ __('main.Password') }}" name="password" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-8">
            <div class="icheck-primary">
              <input type="checkbox" id="remember">
              <label for="remember" style="color: rgb(17 38 134)">
                {{ __('main.Remember me') }}
              </label>
            </div>
          </div>
          <div class="col-4">
            <button type="submit" class="btn btn-primary btn-block">{{ __('main.Sign in') }}</button>
          </div>
        </div>
      </form>

      <!--<div class="social-auth-links text-center mb-3">
        <p>- OR -</p>
        <a href="#" class="btn btn-block btn-primary">
          <i class="fab fa-facebook mr-2"></i> Sign in using Facebook
        </a>
        <a href="#" class="btn btn-block btn-danger">
          <i class="fab fa-google-plus mr-2"></i> Sign in using Google+
        </a>
      </div>

      <p class="mb-1">
        <a href="#">Şifremi Unuttum</a>
      </p>-->
      {{-- <p class="mb-0">
        <a href="{{route('register.user')}}" class="text-center">{{ __('main.Register') }}</a>
      </p> --}}
    </div>
  </div>
</div>
<script src="{{asset('admin')}}/plugins/jquery/jquery.min.js"></script>
<script src="{{asset('admin')}}/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="{{asset('admin')}}/dist/js/adminlte.min.js"></script>

</body>
</html>
