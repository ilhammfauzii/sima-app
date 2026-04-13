<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Halaman login untuk aplikasi BEAM.">
    <meta name="author" content="">
    <title>Login | SIMA</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('img/Favicon.ico') }}">
</head>

<body class="bg-login-full-image">

    <div class="container min-vh-100 d-flex justify-content-center align-items-center">
        <div class="col-md-6">
            <div class="card o-hidden border-0 shadow-lg">
                <div class="card-body p-0">
                    <div class="row">
                        <div class="col-md-5 d-none d-md-block bg-login-image"></div>
                        <div class="col-md-7">
                            <div class="p-5">
                                <div class="d-flex justify-content-center align-items-center">
                                    <img src="{{ asset('img/logo-perusahaan.png') }}" alt="Logo Perusahaan" class="logo-perusahaan">
                                    <h1 class="h2 text-gray-900 mb-1">SIMA</h1>
                                </div>
                                @if ($errors->has('login'))
                                    <div class="alert alert-danger">
                                        {{ $errors->first('login') }}
                                    </div>
                                @endif

                                @if (session('status'))
                                    <div class="alert alert-success">
                                        {{ session('status') }}
                                    </div>
                                @endif

                                <form method="POST" action="{{ route('login') }}" class="user">
                                    @csrf
                                    <div class="form-group">
                                        <label for="nomor_pegawai" class="sr-only">Nomor Pegawai</label>
                                        <input type="text" name="nomor_pegawai" class="form-control form-control-user" id="nomor_pegawai" placeholder="Masukkan Nomor Pegawai..." required>
                                    </div>
                                    <div class="form-group">
                                        <label for="password" class="sr-only">Password</label>
                                        <input type="password" name="password" class="form-control form-control-user" id="password" placeholder="Password" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-user btn-block">
                                        Login
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="copyright-footer" class="text-center">
        <span>2026 &copy; SIMA</span>
    </div>
    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('vendor/jquery-easing/jquery.easing.min.js') }}"></script>
    <script src="{{ asset('js/sb-admin-2.min.js') }}"></script>
    <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,300,400,600,700,800,900" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/sb-admin-2.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/pages/login.css') }}">
</body>
</html>