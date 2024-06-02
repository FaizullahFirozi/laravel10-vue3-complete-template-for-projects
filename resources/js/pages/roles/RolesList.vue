<script setup>
// import axois from 'axios';
import { ref, onMounted } from "vue";
import { useSweetAlert } from "@/components/toastr.js";
import Swal from "sweetalert2";


const roles = ref([]);

const getRoles = () => {
    axios.get("/api/roles").then((response) => {
        roles.value = response.data;
    });
};


// FOR SHOW PERMISSIONS
const role = ref(null);
const loading = ref(false);
const showRolePermissions = async (id) => {
    $("#userFormModal").modal("show");
    loading.value = true;
    try {
        const response = await axios.get(`/api/roles/${id}`);
        role.value = response.data;
    } catch (error) {
        console.error("Error fetching role:", error);
    } finally {
        loading.value = false; // Stop loading spinner
    }
};


// THIS IS ONLY FOR TEST NOT REAL DELETE DATA
const confirmRoleDeletion = (id) => {
    Swal.fire({
        title: "آیا مطمئن یی؟",
        text: "ټیست لپاره دی نه ډیلیټ کیږی 🥰",
        // icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "هو Yes",
        cancelButtonText: "نه No",
    }).then((result) => {
        if (result.isConfirmed) {
            useSweetAlert("مبارک شه", "حذف نشو هسی ټوکه وه😁!");
        }
    });
};

onMounted(() => {
    getRoles();
});
</script>

<template>
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">👈{{ $t("dashboard") }}</h1>
                </div>
                <!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-left">
                        <li class="breadcrumb-item">
                            <router-link to="/admin/dashboard">{{
                                $t("home")
                            }}</router-link>
                        </li>
                    </ol>
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="card">
            <div class="card-header bg-pink">
                <router-link
                    to="/admin/roles/add"
                    class="btn btn-primary float-right"
                >
                    Add Roles <i class="fa fa-plus-circle"></i
                ></router-link>
                <h3 class="card-title ml-4 mt-1">
                    د قوانینو لیست / لیست قوانین
                </h3>

                <div class="card-tools">
                    <button
                        type="button"
                        class="btn btn-tool"
                        data-card-widget="collapse"
                        title="Collapse"
                    >
                        <i class="fas fa-minus"></i>
                    </button>
                    <button
                        type="button"
                        class="btn btn-tool"
                        data-card-widget="remove"
                        title="Remove"
                    >
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <div class="card-body p-0">
                <table class="table table-striped projects">
                    <thead>
                        <tr>
                            <th style="width: 5%">#</th>
                            <th style="width: 15%">Roles Name</th>
                            <th style="width: 15%">Team Members</th>
                            <th>Project Progress</th>
                            <th style="width: 8%" class="text-center">
                                Guard Name
                            </th>
                            <th style="width: 15%; text-align: center">
                                Action
                            </th>
                        </tr>
                    </thead>
                    <transition name="slide-down-fade-frz">
                        <tbody v-if="roles.length > 0">
                            <tr v-for="item in roles" :key="item.id">
                                <td v-text="item.id"></td>
                                <td>
                                    <a v-text="item.name"></a>
                                    <br />
                                    <small v-text="item.name"></small>
                                </td>
                                <td>
                                    <ul class="list-inline">
                                        <li class="list-inline-item">
                                            <img
                                                alt="Avatar"
                                                class="table-avatar"
                                                src="/storage/users_avatar/cGk777o22j8J56uHZolZ8zs4mqdOzNRM5JRZ5X6k.png"
                                            />
                                        </li>
                                        <li class="list-inline-item">
                                            <img
                                                alt="Avatar"
                                                class="table-avatar"
                                                src="/logo.png"
                                            />
                                        </li>
                                    </ul>
                                </td>
                                <td class="project_progress">
                                    <div class="progress progress-sm">
                                        <div
                                            class="progress-bar bg-green"
                                            role="progressbar"
                                            aria-valuenow="57"
                                            aria-valuemin="0"
                                            aria-valuemax="100"
                                            style="width: 57%"
                                        ></div>
                                    </div>
                                    <small> 57% Complete </small>
                                </td>
                                <td class="project-state">
                                    <span
                                        class="badge badge-success"
                                        v-text="item.guard_name"
                                    ></span>
                                </td>
                                <td
                                    v-if="item.id !== 1"
                                    class="project-actions text-left"
                                >
                                    <a
                                        @click.prevent="
                                            showRolePermissions(item.id)
                                        "
                                        class="btn btn-warning btn-sm mr-1"
                                        href="#"
                                    >
                                        <i class="fas fa-eye"> </i>
                                        View
                                    </a>

                                    <router-link
                                        :to="`/admin/roles/${item.id}/edit`"
                                        class="btn btn-info bg-success btn-sm mr-1"
                                        ><i class="fas fa-pencil-alt"> </i>
                                        Edit</router-link
                                    >

                                    <a
                                        @click.prevent="
                                            confirmRoleDeletion(item.id)
                                        "
                                        class="btn btn-danger btn-sm mr-1"
                                        href="#"
                                    >
                                        <i class="fas fa-trash"> </i>
                                        Delete
                                    </a>
                                </td>
                                <td
                                    v-if="item.id === 1"
                                    class="text-center"
                                >
                                <small>
                                    د Super Admin قوانین نه تغیر کیږی 
                                </small>
                                </td>
                            </tr>
                        </tbody>
                        <tbody v-else>
                            <tr>
                                <td colspan="13" align="center">
                                    مهربانی وکړئ لږ انتظار شئ...
                                    <div
                                        class="spinner-border text-pink"
                                    >
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </transition>
                </table>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </section>
    <!-- /.content -->

    <!-- The Create & Edit Modal -->
    <div
        class="modal fade mt-5"
        id="userFormModal"
        data-backdrop="static"
        data-keyboard="true"
        tabindex="-1"
        aria-labelledby="staticBackdropLabel"
        aria-hidden="true"
    >
        <div class="modal-dialog modal-sm text-center">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header bg-warning">
                    <button
                        type="button"
                        class="close bg-danger"
                        data-dismiss="modal"
                    >
                        &times;
                    </button>
                    <h4 class="modal-title">
                        <span>All permissions</span>
                    </h4>
                </div>
                <div>
                    <button
                        v-if="!loading"
                        class="btn btn-outline-success btn-sm m-3"
                        @click="showRolePermissions()"
                    >
                        ReLoad
                    </button>
                    <!-- Example button to load role permissions -->
                    <div
                        v-if="loading"
                        class="spinner-border text-warning m-3"
                        role="status"
                    >
                        <span class="sr-only">Loading...</span>
                    </div>
                    <div v-else>
                        <div v-if="role">
                            <h2 class="text-purple border pb-2">
                                {{ role.name }}
                            </h2>
                            <ol>
                                <li
                                    v-for="permission in role.permissions"
                                    :key="permission"
                                >
                                    {{ permission.name }}
                                </li>
                            </ol>
                        </div>
                        <div v-else>No role data available.</div>
                    </div>
                </div>
                <!-- Modal body -->
            </div>
        </div>
    </div>
</template>
<style>
.slide-down-fade-frz-enter-from {
    opacity: 0;
    transform: translateY(-30px);
}
.slide-down-fade-frz-enter-active {
    transition: all 1s ease;
}
</style>
