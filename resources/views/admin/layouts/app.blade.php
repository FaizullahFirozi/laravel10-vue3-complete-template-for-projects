<!DOCTYPE html>
<!--if you want to change te Language to English change dir="ltr" -->
<html lang="en" dir="rtl">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AdminLTE 3 | شروع صفحه</title>

    <!-- PWA START  -->
    <meta name="theme-color" content="#6777ef" />
    <link rel="apple-touch-icon" href="{{ asset('logo.png') }}">
    <link rel="manifest" href="{{ asset('/manifest.json') }}">
    <!-- PWA END  -->


    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{-- @vite(['resources/css/app_rtl.css', 'resources/js/app.js']) --}}

</head>

<body class="hold-transition sidebar-mini  layout-fixed">

    <div class="wrapper" id="app">
        {{-- name="loading"  --}}
        {{-- name="spinning"  --}}
        {{-- name="dots"  --}}
        {{-- name="circular"  --}}
        <loader_vue id="loader_vue_show" name="loading" loadingText="youuuuu..." textColor="#ffffff" textSize="15"
            textWeight="800" object="#ff9633" color1="#ffffff" color2="#17fd3d" size="5" speed="2"
            bg="#343a40" objectbg="#999793" opacity="80" :disableScrolling="false"></loader_vue>
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-light">

            {{-- NAVBAR SECTION FROM VUE --}}
            <navbar_vue></navbar_vue>
            {{-- NAVBAR SECTION FROM VUE --}}

        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-indigo elevation-4">
            <!-- Brand Logo -->
            <a href="index3.html" class="brand-link">
                <img src="{{ asset('uploads/AdminLTELogo.png') }}" alt="Logo"
                    class="brand-image img-circle elevation-3" style="opacity: .8">
                <span class="brand-text font-weight-light">د مدیرت لیکدړه</span>
            </a>

            <!-- Sidebar -->
            <div class="sidebar">

                {{-- SIDEBAR SECTION FROM VUE --}}
                <sidebar_vue></sidebar_vue>
                {{-- SIDEBAR SECTION FROM VUE --}}

                <li class="nav-item">
                    <form method="POST" action="{{ route('logout') }}" class="nav-link">
                        @csrf
                        <a href="#" onclick="event.preventDefault(); this.closest('form').submit();" class="nav-link">
                            <i class="nav-icon fas fa-sign-out-alt"></i>
                            <p>
                                Logout
                            </p>
                        </a>
                    </form>
                </li>
            </div>
            <!-- /.sidebar -->


        </aside>
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">

            {{-- COME HRERE FROME VUE FILE ALL CONTETNS --}}
            <router-view></router-view>
            {{-- COME HRERE FROME VUE FILE ALL CONTETNS --}}


        </div>
        <!-- /.content-wrapper -->

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
            <div class="p-3">
                <h5>Title</h5>
                <p>Sidebar content</p>
            </div>
        </aside>
        <!-- /.control-sidebar -->

        <!-- Main Footer -->
        <footer class="main-footer">
            <footer_vue></footer_vue>
        </footer>
    </div>
    <!-- ./wrapper -->

    <script>
        // Faizullah Firozi. This code is only for ****START**** Collapse the SIDEBAR 
        document.addEventListener('DOMContentLoaded', () => {
            const toggleMenuIcon = document.getElementById('toggleMenuIcon');

            const body = document.querySelector('body');

            toggleMenuIcon.addEventListener('click', () => {
                if (body.classList.contains('sidebar-collapse')) {
                    localStorage.setItem('sidebarState', 'expanded');
                } else {
                    localStorage.setItem('sidebarState', 'collapse');
                }
            });

            const sideBarState = localStorage.getItem('sidebarState');

            if (sideBarState === 'collapse') {
                body.classList.add('sidebar-collapse');
            }
        });
        // Faizullah Firozi. This code is only for ****END**** Collapse the SIDEBAR 




        // Dark Mode theme *****START***** 
        document.addEventListener('DOMContentLoaded', () => {
            const themeIcon = document.getElementById('themeModeFRZ');
            const body = document.querySelector('body');
            const nav = document.querySelector('nav');
            const icon = document.querySelector('.mode_icon_frz');

            // Function to apply the theme based on localStorage
            const applyThemeMode = () => {
                const themeMode = localStorage.getItem('themeMode');
                if (themeMode === 'dark') {
                    body.classList.add('dark-mode');
                    nav.classList.remove('navbar-light');
                    nav.classList.add('navbar-dark');
                    icon.classList.remove('fa-moon');
                    icon.classList.add('fa-sun');


                } else {
                    body.classList.remove('dark-mode');
                    nav.classList.remove('navbar-dark');
                    nav.classList.add('navbar-light');
                    icon.classList.remove('fa-sun');
                    icon.classList.add('fa-moon');


                }
            };

            // Event listener for theme mode toggle
            themeIcon.addEventListener('click', () => {
                if (body.classList.contains('dark-mode')) {
                    localStorage.setItem('themeMode', 'light');
                } else {
                    localStorage.setItem('themeMode', 'dark');
                }
                applyThemeMode(); // Apply the theme after toggling
            });
            // Apply the theme mode on page load
            applyThemeMode();
        });
        // Dark Mode theme *****END***** 
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const loader_vue_show = document.getElementById('loader_vue_show');
            // Show the loader after 1 second
            setTimeout(function() {
                loader_vue_show.style.display = 'none'
            }, 1000);
        });
    </script>

    <!-- PWA START DESKTOP APPLICATION CREATED  -->
    <script src="{{ asset('/sw.js') }}"></script>
    <script>
        if ("serviceWorker" in navigator) {
            // Register a service worker hosted at the root of the
            // site using the default scope.
            navigator.serviceWorker.register("/sw.js").then(
                (registration) => {
                    console.log("Service worker registration succeeded:", registration);
                },
                (error) => {
                    console.error(`Service worker registration failed: ${error}`);
                },
            );
        } else {
            console.error("Service workers are not supported.");
        }
    </script>
    <!-- PWA END  DESKTOP APPLICATION -->

</body>

</html>
