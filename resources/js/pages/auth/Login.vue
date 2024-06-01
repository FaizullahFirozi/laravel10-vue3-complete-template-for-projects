<script setup>
import axios from "axios";
import { reactive, ref } from "vue";
import { useToastrError } from "@/components/toastr";

const form = reactive({
    email: "",
    password: "",
    remember: "",
});

const loading_spinner = ref(false);
const errors = ref("");
const handleSubmit = () => {
    loading_spinner.value = true;
    errors.value = "";
    axios
        .post("/login", form)
        .then(() => {
            window.location.href = "/admin/dashboard";
        })
        .catch((error) => {
            if ((error.response && error.response.status === 422) || 429) {
                errors.value = error.response.data.errors;
                useToastrError("مشکل", "چاره ناکامه وه. بیا هڅه وکړئ");
            }
        })
        .finally(() => {
            loading_spinner.value = false;
        });
};
</script>
<template>
    <body class="hold-transition login-page">
        <div class="login-box">
            <!-- /.login-logo -->
            <div class="card card-outline card-primary">
                <div class="card-header text-center">
                    <a href="#" class="h1"><b>Admin</b>LTE</a>
                </div>
                <div class="card-body">
                    <p class="login-box-msg">Sign in to start your session</p>

                    <!-- <div v-if="errors" class="alert alert-danger text-left">
                    {{ errors.email[0] }}
                    <br>
                    {{ errors.password[0] }}
                </div> -->
                    <form @submit.prevent="handleSubmit">
                        <div class="input-group mb-3">
                            <input
                                v-model="form.email"
                                :autofocus="true"
                                type="email"
                                class="form-control"
                                placeholder="Email"
                                :class="{ 'is-invalid': errors.email }"
                            />

                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-envelope"></span>
                                </div>
                            </div>
                            <span
                                class="invalid-feedback"
                                v-if="errors && errors.email"
                                >{{ errors.email[0] }}</span
                            >
                        </div>
                        <div class="input-group mb-3">
                            <input
                                v-model="form.password"
                                type="password"
                                class="form-control"
                                placeholder="Password"
                                autocomplete="autocomplete"
                                :class="{ 'is-invalid': errors.password }"
                            />
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-lock"></span>
                                </div>
                            </div>
                            <span
                                class="invalid-feedback"
                                v-if="errors && errors.password"
                                >{{ errors.password[0] }}</span
                            >
                        </div>
                        <div class="row">
                            <div class="col-8">
                                <div class="icheck-primary float-left">
                                    <input type="checkbox" v-model="form.remember" id="remember" />
                                    <label for="remember"> Remember Me </label>
                                </div>
                            </div>
                            <!-- /.col -->
                            <div class="col-4">
                                <button
                                    type="submit"
                                    class="btn btn-primary btn-block"
                                    :disabled="loading_spinner"
                                >
                                    <div
                                        v-if="loading_spinner"
                                        class="spinner-border spinner-border-sm"
                                        role="status"
                                    >
                                        <span class="sr-only"
                                            >loading_spinner...</span
                                        >
                                    </div>
                                    <span v-else> Sign In </span>
                                </button>
                            </div>
                            <!-- /.col -->
                        </div>
                    </form>

                    <div class="social-auth-links text-center mt-2 mb-3">
                        <a href="#" class="btn btn-block btn-primary">
                            <i class="fab fa-facebook mr-2"></i> Sign in using
                            Facebook
                        </a>
                        <a href="#" class="btn btn-block btn-danger">
                            <i class="fab fa-google-plus mr-2"></i> Sign in
                            using Google+
                        </a>
                    </div>
                    <!-- /.social-auth-links -->

                    <p class="mb-1 float-left">
                        <a href="forgot-password.html">I forgot my password</a>
                    </p>
                    <p class="mb-0 float-left">
                        <a href="register" class="text-center"
                            >Register a new membership</a
                        >
                    </p>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
    </body>
</template>
