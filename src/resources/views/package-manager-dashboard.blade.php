<!DOCTYPE html>
<html lang="en">
<head>
  <title>Laravel Package Manager</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{csrf_token()}}">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <style>
    .card{
        height: 150px;
      }
      .card-body{
        overflow-y: auto !important;
        max-height: 240px !important;
        font-size: 15px !important;
      }
  </style>
</head>
<body>


<nav class="navbar navbar-expand-sm bg-dark navbar-dark">
  <a class="navbar-brand" href="#">Laravel Package Manager</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="collapsibleNavbar">

  </div>
</nav>

<div class="container" style="margin-top:30px" id="artisan-ui">
	  
</div>
</body>
{{--  <script src="{{ asset('vendor/asif/artisan-ui/src/dist/js/app.js') }}"></script>  --}}
</html>
