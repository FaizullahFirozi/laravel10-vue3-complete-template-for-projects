<script setup>
import { ref, onMounted } from "vue";

const menuIcon = ref("fas fa-bars");

const toggleMenu = () => {
    if (menuIcon.value === "fas fa-bars") {
        menuIcon.value = "fas fa-times"; // Change to cross icon
    } else {
        menuIcon.value = "fas fa-bars"; // Change back to bars icon
    }
};

const menuIconStyle = ref("fas fa-th-large");

const styleSidebar = () => {
    if (menuIconStyle.value === "fas fa-th-large") {
        menuIconStyle.value = "fas fa-th"; // Change to cross icon
    } else {
        menuIconStyle.value = "fas fa-th-large"; // Change back to bars icon
    }
};

const changeLanguage = (lang) => {
    if (lang === "EN") {
        document.cookie = "locale=EN=";
    } else if (lang === "FA") {
        document.cookie = "locale=FA=";
    } else if (lang === "PS") {
        document.cookie = "locale=PS=";
    }
    // window.location.reload();
};

onMounted(() => {
    // document.cookie = 'locale=FA='
});
</script>

<template>
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item" id="toggleMenuIcon">
            <a
                class="nav-link"
                data-widget="pushmenu"
                href="#"
                role="button"
                @click.prevent="toggleMenu"
                ><i :class="menuIcon"></i
            ></a>
        </li>
        <li class="nav-item" id="themeModeFRZ">
            <a class="nav-link" href="#" role="button"
                ><i class="mode_icon_frz fas fa-moon"></i
            ></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <router-link
                to="/admin/dashboard"
                active-class="active"
                class="nav-link"
                >Home</router-link
            >
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="#" class="nav-link">Contact</a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="#" class="nav-link">{{ $t("home") }}</a>
        </li>
    </ul>

    <!-- left navbar links -->
    <ul class="navbar-nav ml-auto">
        <!-- Navbar Search -->
        <li class="nav-item">
            <a
                class="nav-link"
                data-widget="navbar-search"
                href="#"
                role="button"
            >
                <i class="fas fa-search"></i>
            </a>
            <div class="navbar-search-block">
                <form class="form-inline">
                    <div class="input-group input-group-sm">
                        <input
                            class="form-control form-control-navbar"
                            type="search"
                            placeholder="Search"
                            aria-label="Search"
                        />
                        <div class="input-group-append">
                            <button class="btn btn-navbar" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                            <button
                                class="btn btn-navbar"
                                type="button"
                                data-widget="navbar-search"
                            >
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </li>

        <!-- Messages Dropdown Menu -->
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="far fa-comments"></i>
                <span class="badge badge-danger navbar-badge">3</span>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-left">
                <a href="#" class="dropdown-item">
                    <!-- Message Start -->
                    <div class="media">
                        <img
                            src="dist/img/user1-128x128.jpg"
                            alt="User Avatar"
                            class="img-size-50 mr-3 img-circle"
                        />
                        <div class="media-body">
                            <h3 class="dropdown-item-title">
                                Brad Diesel
                                <span class="float-left text-sm text-danger"
                                    ><i class="fas fa-star"></i
                                ></span>
                            </h3>
                            <p class="text-sm">Call me whenever you can...</p>
                            <p class="text-sm text-muted">
                                <i class="far fa-clock mr-1"></i> 4 Hours Ago
                            </p>
                        </div>
                    </div>
                    <!-- Message End -->
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                    <!-- Message Start -->
                    <div class="media">
                        <img
                            src="dist/img/user8-128x128.jpg"
                            alt="User Avatar"
                            class="img-size-50 img-circle mr-3"
                        />
                        <div class="media-body">
                            <h3 class="dropdown-item-title">
                                John Pierce
                                <span class="float-left text-sm text-muted"
                                    ><i class="fas fa-star"></i
                                ></span>
                            </h3>
                            <p class="text-sm">I got your message bro</p>
                            <p class="text-sm text-muted">
                                <i class="far fa-clock mr-1"></i> 4 Hours Ago
                            </p>
                        </div>
                    </div>
                    <!-- Message End -->
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                    <!-- Message Start -->
                    <div class="media">
                        <img
                            src="dist/img/user3-128x128.jpg"
                            alt="User Avatar"
                            class="img-size-50 img-circle mr-3"
                        />
                        <div class="media-body">
                            <h3 class="dropdown-item-title">
                                Nora Silvester
                                <span class="float-left text-sm text-warning"
                                    ><i class="fas fa-star"></i
                                ></span>
                            </h3>
                            <p class="text-sm">The subject goes here</p>
                            <p class="text-sm text-muted">
                                <i class="far fa-clock mr-1"></i> 4 Hours Ago
                            </p>
                        </div>
                    </div>
                    <!-- Message End -->
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item dropdown-footer"
                    >See All Messages</a
                >
            </div>
        </li>
        <!-- Notifications Dropdown Menu -->
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="far fa-bell"></i>
                <span class="badge badge-warning navbar-badge">15</span>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-left">
                <span class="dropdown-header">15 Notifications</span>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                    <i class="fas fa-envelope mr-2"></i> 4 new messages
                    <span class="float-left text-muted text-sm">3 mins</span>
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                    <i class="fas fa-users mr-2"></i> 8 friend requests
                    <span class="float-left text-muted text-sm">12 hours</span>
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                    <i class="fas fa-file mr-2"></i> 3 new reports
                    <span class="float-left text-muted text-sm">2 days</span>
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item dropdown-footer"
                    >See All Notifications</a
                >
            </div>
        </li>
        <!-- Language Dropdown Menu -->
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="flag-icon flag-icon-sa"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-left p-0 text-center">
                <a
                    href="#"
                    @click.prevent="changeLanguage((lang = 'PS'))"
                    class="dropdown-item"
                >
                    <span @click="$i18n.locale = `PS`">
                    <i class="flag-icon flag-icon-af ml-2"></i>
                         Pashto </span>
                </a>
                <a
                    href="#"
                    @click.prevent="changeLanguage((lang = 'FA'))"
                    class="dropdown-item"
                >
                    <span @click="$i18n.locale = `FA`">
                        <i class="flag-icon flag-icon-tj ml-2"></i>
                        Dari
                    </span>
                </a>
                <a
                    href="#"
                    @click.prevent="changeLanguage((lang = 'EN'))"
                    class="dropdown-item"
                >
                    <span @click="$i18n.locale = `EN`">
                        <i class="flag-icon flag-icon-us ml-2"></i>
                        English
                    </span>
                </a>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                <i class="fas fa-expand-arrows-alt"></i>
            </a>
        </li>
        <li class="nav-item">
            <a
                @click.prevent="styleSidebar"
                class="nav-link"
                data-widget="control-sidebar"
                data-slide="true"
                href="#"
                role="button"
            >
                <i :class="menuIconStyle"></i>
            </a>
        </li>
    </ul>
</template>
