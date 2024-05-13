<script setup>
import axios from "axios";
import { onMounted, reactive, ref } from "vue";

import { useToastr } from "@/toastr";
import Swal from "sweetalert2";

const toastr = useToastr();

const form = ref({
    name: "",
    email: "",
});

// FOR GETTING DATA
const getUser = () => {
    axios.get("/api/profile").then((response) => {
        form.value = response.data;
    });
};

// UPDATE PROFILE SECTION
const errors = ref([]);
const loading_spinner = ref(false);

const updateProfile = () => {
    errors.value = "";
    loading_spinner.value = true;

    axios
        .put("/api/profile", form.value)
        .then((response) => {
            toastr.success("د کارمند معلومات تعغیر شول", "مبارک شه");
            // toastr.success(response.data.message, response.data.title);

            //*****Sweet Alert*****
            Swal.fire({
                position: "top-end",
                icon: "success",
                title: "معلومات تعغیر شول",
                showConfirmButton: false,
                timer: 1500,
            });
        })
        .catch((error) => {
            // setFieldError("email", error.response.data.errors.email);
            // setFieldError("name", error.response.data.errors.name);

            if (error.response && error.response.status === 422) {
                errors.value = error.response.data.errors;
                toastr.error("validation error", "مشکل");
            }
        })
        .finally(() => {
            loading_spinner.value = false;
        });
};

//START UPDATE PASSWORD SECTION
const changePasswordForm = reactive({
    currentPassword: '',
    password: '',
    passwordConfirmatiton: '',
});

const handleChangePassword = () => {
    errors.value = "";
    loading_spinner.value = true;

    axios
        .post("/api/change-user-password", changePasswordForm)
        .then((response) => {
            toastr.success(response.data.message);
            // toastr.success('ستاسو پسورد تعغیر شو.', 'چاره بریالۍ وه!')
        })
        .catch((error) => {
            errors.value = error.response.data.errors;
            toastr.error("ستاسو پسورد تعغیر نشو.", "چاره ناکامه وه!");
        })
        .finally(() => {
            loading_spinner.value = false;
        });
};
//END UPDATE PASSWORD SECTION


// THIS SECTION IS FOR UPLADING PICTURE OR IMAGE
const fileInput = ref(null);
const openFileInput = () => {
    fileInput.value.click();
};

const profilePictureUrl = ref(null);

const handleFileChange = (event) => {
    const file = event.target.files[0];
    profilePictureUrl.value = URL.createObjectURL(file);

    const formData = new FormData();
    formData.append("profile_picture", file);

    axios.post("/api/upload-profile-image", formData).then((response) => {
        toastr.success("عکس په بریالی توګه اضافه شو", "اضافه شو");
    });
};

onMounted(() => {
    getUser();
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
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-3">
                    <!-- Profile Image -->
                    <div class="card card-primary card-outline">
                        <div class="card-body box-profile">
                            <div class="text-center">
                                <input
                                    @change="handleFileChange"
                                    ref="fileInput"
                                    type="file"
                                    class="d-none"
                                />
                                <img
                                    @click="openFileInput"
                                    class="profile-user-img img-fluid img-circle"
                                    :src="
                                        profilePictureUrl
                                            ? profilePictureUrl
                                            : '/logo.png'
                                    "
                                    alt="User profile picture"
                                />
                            </div>
                            <h3 class="profile-username text-center text-pink">
                                {{ form.name }}
                            </h3>
                            <p
                                class="text-muted text-center"
                                v-text="form.email"
                            ></p>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
                <div class="col-md-9">
                    <div class="card">
                        <div class="card-header p-2">
                            <ul class="nav nav-pills">
                                <li class="nav-item">
                                    <a
                                        class="nav-link active"
                                        href="#editProfile"
                                        data-toggle="tab"
                                        ><i class="nav-icon fa fa-user"> </i>
                                        Edit Profile
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a
                                        class="nav-link"
                                        href="#changePassword"
                                        data-toggle="tab"
                                    >
                                        <i class="nav-icon fa fa-key"> </i>
                                        Change Password
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="tab-content">
                                <div class="active tab-pane" id="editProfile">
                                    <form
                                        @submit.prevent="updateProfile()"
                                        class="form-horizontal"
                                    >
                                        <div class="form-group row">
                                            <label
                                                for="inputName"
                                                class="col-sm-2 col-form-label"
                                                >Name</label
                                            >
                                            <div class="col-sm-10">
                                                <input
                                                    v-model="form.name"
                                                    type="text"
                                                    class="form-control"
                                                    placeholder="Name"
                                                    :class="{
                                                        'is-invalid':
                                                            errors.name,
                                                    }"
                                                />
                                                <span
                                                    class="invalid-feedback"
                                                    v-if="errors && errors.name"
                                                    >{{ errors.name[0] }}</span
                                                >
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label
                                                for="inputEmail"
                                                class="col-sm-2 col-form-label"
                                                >Email</label
                                            >
                                            <div class="col-sm-10">
                                                <input
                                                    v-model="form.email"
                                                    type="email"
                                                    class="form-control"
                                                    id="inputEmail"
                                                    placeholder="Email"
                                                    :class="{
                                                        'is-invalid':
                                                            errors.email,
                                                    }"
                                                    required
                                                />
                                                <span
                                                    class="invalid-feedback"
                                                    v-if="
                                                        errors && errors.email
                                                    "
                                                    >{{ errors.email[0] }}</span
                                                >
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="offset-sm-2 col-sm-10">
                                                <button
                                                    type="submit"
                                                    class="btn btn-success"
                                                    :disabled="loading_spinner"
                                                >
                                                    <div
                                                        v-if="loading_spinner"
                                                        class="spinner-border spinner-border-sm"
                                                    >
                                                        <span class="sr-only"
                                                            >loading_spinner...</span
                                                        >
                                                    </div>
                                                    <span v-else>
                                                        <i
                                                            class="nav-icon fa fa-save"
                                                        >
                                                        </i>
                                                        Update
                                                    </span>
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <!-- /.tab-pane -->
                                <div class="tab-pane" id="changePassword">
                                    <form
                                        @submit.prevent="handleChangePassword"
                                        class="form-horizontal"
                                    >
                                        <div class="form-group row">
                                            <label
                                                class="col-sm-2 col-form-label"
                                                >Current Password</label
                                            >
                                            <div class="col-sm-10">
                                                <input
                                                    v-model="
                                                        changePasswordForm.currentPassword
                                                    "
                                                    type="password"
                                                    class="form-control"
                                                    placeholder="Current Password"
                                                    :class="{
                                                        'is-invalid':
                                                            errors.current_password
                                                    }"
                                                    required
                                                    autocomplete
                                                />
                                                <span
                                                    class="invalid-feedback"
                                                    v-if="errors && errors.current_password"
                                                    >{{ errors.current_password[0] }}</span
                                                >
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label
                                                class="col-sm-2 col-form-label"
                                                >New Password</label
                                            >
                                            <div class="col-sm-10">
                                                <input
                                                    v-model="
                                                        changePasswordForm.password
                                                    "
                                                    type="password"
                                                    class="form-control"
                                                    placeholder="New Password"
                                                    :class="{
                                                        'is-invalid':
                                                            errors.password
                                                    }"
                                                    required
                                                    autocomplete
                                                />
                                                <span
                                                    class="invalid-feedback"
                                                    v-if="errors && errors.password"
                                                    >{{ errors.password[0] }}</span
                                                >
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label
                                                class="col-sm-2 col-form-label"
                                                >Confirm New Password</label
                                            >
                                            <div class="col-sm-10">
                                                <input
                                                    v-model="
                                                        changePasswordForm.passwordConfirmatiton
                                                    "
                                                    type="password"
                                                    class="form-control"
                                                    placeholder="Confirm New Password"
                                                    :class="{
                                                        'is-invalid':
                                                            errors.password
                                                    }"
                                                    required
                                                    autocomplete
                                                />
                                                <span
                                                    class="invalid-feedback"
                                                    v-if="errors && errors.password"
                                                    >{{ errors.password[0] }}</span
                                                >
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <div class="offset-sm-2 col-sm-10">
                                                <button
                                                    type="submit"
                                                    class="btn btn-success"
                                                    :disabled="loading_spinner"
                                                >
                                                    <div
                                                        v-if="loading_spinner"
                                                        class="spinner-border spinner-border-sm"
                                                    >
                                                        <span class="sr-only"
                                                            >loading_spinner...</span
                                                        >
                                                    </div>
                                                    <span v-else>
                                                        <i
                                                            class="nav-icon fa fa-save"
                                                        >
                                                        </i>
                                                        Change
                                                    </span>
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <!-- /.tab-pane -->
                            </div>
                            <!-- /.tab-content -->
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</template>

<style>
.profile-user-img:hover {
    background-color: rgb(43, 255, 0);
    cursor: pointer;
}
</style>
