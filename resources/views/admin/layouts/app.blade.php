<!DOCTYPE html>
<!--if you want to change te Language to English change dir="ltr" -->
<html lang="en" dir="rtl">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AdminLTE 3 | شروع صفحه</title>


    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{-- @vite(['resources/css/app_rtl.css', 'resources/js/app.js']) --}}


</head>

<body class="hold-transition sidebar-mini  layout-fixed">
    <div class="wrapper" id="app">

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

</body>

</html>
