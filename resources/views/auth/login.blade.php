<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title> Log in</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    {{-- @vite(['resources/css/app_rtl.css', 'resources/js/app.js']) --}}

</head>

<body class="hold-transition login-page">

    <div id="app">
        <router-view>
            <Login_vue />
        </router-view>
        
    </div>
</body>

</html>
