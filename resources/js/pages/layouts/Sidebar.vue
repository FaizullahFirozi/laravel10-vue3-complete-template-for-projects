<script>
export default {
    data() {
        //FOR TEST PORPUSE ONLY
        return {
            searchQuerySidebar: "",
            showModal: false,
            sidebarItems: [
                { id: 1, name: "Dashboard", searchLink: "/admin/dashboard" },
                { id: 2, name: "Dashboard2", searchLink: "/admin/dashboard2" },
                { id: 3, name: "CRUD-Test", searchLink: "/admin/CRUD-Test" },
                { id: 4, name: "User", searchLink: "/admin/users" },
                { id: 5, name: "Profile", searchLink: "/admin/profile" },
                {
                    id: 5,
                    name: "Activity Log",
                    searchLink: "/admin/activity-log",
                },
                // Add more sidebar items as needed
            ],
        };
    },
    computed: {
        filteredItems() {
            return this.sidebarItems.filter((item) => {
                return item.name
                    .toLowerCase()
                    .includes(this.searchQuerySidebar.toLowerCase());
            });
        },
    },
    methods: {
        filterSidebarItems() {
            this.showModal = this.searchQuerySidebar.length > 0; // Show modal if search query is not empty
        },
        closeModal() {
            this.showModal = false;
        },

        // FOR CLEAR SEARCHBAR
        clearSearchSidebar() {
            if (this.searchQuerySidebar) {
                this.searchQuerySidebar = "";
            }
        },
    },
};
</script>
<template>
    <!-- SidebarSearch Form -->
    <div class="form-inline">
        <div class="input-group" data-widget="sidebar-search">
            <input
                v-model="searchQuerySidebar"
                @input="filterSidebarItems"
                class="form-control form-control-sidebar"
                type="search"
                placeholder="Search"
                aria-label="Search"
            />
            <div class="input-group-append">
                <button @click="clearSearchSidebar" class="btn btn-sidebar">
                    <i class="fas fa-search fa-fw"></i>
                </button>
            </div>
        </div>

        <div class="modal" :class="{ 'is-active': showModal }">
            <div class="modal-background" @click="closeModal"></div>
            <div class="modal-content">
                <ul class="sidebar-menu">
                    <li v-for="item in filteredItems" :key="item.id">
                        <router-link to="{{item.searchLink}}"
                            ><p>{{ item.name }}</p></router-link
                        >
                    </li>
                </ul>
            </div>
            <button
                class="modal-close is-large"
                aria-label="close"
                @click="closeModal"
            ></button>
        </div>
    </div>

    <!-- Sidebar Menu -->
    <nav class="mt-2">
        <ul
            class="nav nav-pills nav-sidebar flex-column"
            data-widget="treeview"
            role="menu"
            data-accordion="false"
        >
            <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
            <li class="nav-item">
                <router-link
                    to="/admin/dashboard"
                    active-class="active"
                    class="nav-link"
                >
                    <i class="nav-icon fas fa-tachometer-alt"></i>
                    <p>{{ $t('dashboard') }}</p>
                </router-link>
            </li>
            <li class="nav-item">
                <router-link
                    to="/admin/dashboard2"
                    active-class="active"
                    class="nav-link"
                >
                    <i class="nav-icon fas fa-tachometer-alt"></i>
                    <p>{{ $t('dashboard2') }}</p>
                </router-link>
            </li>
            <li class="nav-item">
                <router-link
                    to="/admin/CRUD-Test"
                    active-class="active"
                    class="nav-link"
                    ><i class="nav-icon fa fa-syringe"></i>
                    <p>CRUD Test</p>
                </router-link>
            </li>

            <li class="nav-item">
                <router-link
                    to="/admin/users"
                    active-class="active"
                    class="nav-link"
                    ><i class="nav-icon fa fa-user"></i>
                    <p>{{ $t('users') }}</p>
                </router-link>
            </li>
            <li class="nav-item">
                <router-link
                    to="/admin/profile"
                    active-class="active"
                    class="nav-link"
                    ><i class="nav-icon fa fa-user"></i>
                    <p>{{ $t('profile') }}</p>
                </router-link>
            </li>
            <li class="nav-item">
                <router-link
                    to="/admin/roles"
                    active-class="active"
                    class="nav-link"
                    ><i class="nav-icon fa fa-user"></i>
                    <p>{{ $t('roles') }}</p>
                </router-link>
            </li>
            <li class="nav-item">
                <router-link
                    to="/admin/activity-log"
                    active-class="active"
                    class="nav-link"
                    ><i class="nav-icon fa fa-book"></i>
                    <p>{{ $t('activity_log') }}</p>
                </router-link>
            </li>

            <li class="nav-item menu-open">
                <a href="#" class="nav-link active">
                    <i class="nav-icon fas fa-tachometer-alt"></i>
                    <p>
                        Starter Pages
                        <i class="left fas fa-angle-left"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="#" class="nav-link active">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Active Page</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Inactive Page</p>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="nav-icon fas fa-th"></i>
                    <p>
                        Simple Link
                        <span class="left badge badge-danger">New</span>
                    </p>
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="nav-icon fas fa-plus"></i>
                    <p>
                        {{ $t("home") }}
                    </p>
                </a>
            </li>
        </ul>
    </nav>
    <!-- /.sidebar-menu -->
</template>
