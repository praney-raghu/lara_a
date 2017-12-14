<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <style type="text/css">
        .thumbnail {
            width: 280px;
            height: 380px;
        }
        .text-capitalize {
            font-weight: bold;
        }
        .txtbold {
            font-weight: bold;
            color: red;
        }
        .footer {
            background-color: white;
        }
        .se-img {
            width: 200px;
            height: 250px;
        }
    </style>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-default navbar-static-top">
            <div class="container">
                <div class="navbar-header">

                    <!-- Collapsed Hamburger -->
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <!-- Branding Image -->
                    <a class="navbar-brand" href="#">
                        {{ config('app.name', 'Laravel') }}
                    </a>
                </div>

                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <!-- Left Side Of Navbar -->
                    <ul class="nav navbar-nav">
                        &nbsp;
                    </ul>
                    <!-- Right Side Of Navbar -->
                    
                </div>
            </div>
        </nav>

        <div class='col-lg-4 col-lg-offset-4'>
	    	<h1><center>401<br>
	    	ACCESS DENIED</center></h1>
	    	<br>
	    	<p><h3>Your Account Activation is due with Admin of Autovilla.
	    	You can login only after activation of your account.
	    	You will receive an email when your account gets activated.</h3></p>
		</div>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    
    
</body>
</html>