<script setup>
import axios from "axios";
import { ref, onMounted, reactive } from "vue";
import { useSweetAlert, useToastrError } from "@/components/toastr.js";
import { useRouter, useRoute } from "vue-router";

// Retrieve the router and route objects
const router = useRouter();
const route = useRoute();

// Extract the role ID from the route parameters
const roleId = route.params.id;

const goBack = () => {
    router.go(-1);
};

// VUE MODELS
const permissions = ref([]);
const formData = reactive({ name: "", permissions: [] });
const errors = ref([]);
const loading_spinner = ref(false);

const getPermissions = () => {
    axios.get("/api/roles/create").then((response) => {
        permissions.value = response.data;
    });
};

const getRoleData = () => {
    axios.get(`/api/roles/${roleId}/edit`).then((response) => {
        const data = response.data;
        formData.name = data.name;
        formData.permissions = data.permissions.map(permission => permission.name);
    }).catch((error) => {
        useToastrError("Error fetching role data", error.message);
    });
};

// ADDING NOTIFICATION MESSAGE...
const playNotificationSound = () => {
    const audio = new Audio("/notifi_sound.wav");
    audio.play();
};

const cancelButton = () => {
    goBack();
};

const submitForm = () => {
    errors.value = "";
    loading_spinner.value = true;

    axios
        .put(`/api/roles/${roleId}`, formData)  // Use PUT request for updating
        .then((response) => {
            playNotificationSound();
            useSweetAlert("مبارک شه", "قوانین اصلاح شول");
            goBack(); // GO BACK
        })
        .catch((error) => {
            if (error.response && error.response.status === 422) {
                errors.value = error.response.data.errors;

                useToastrError(
                    "Validation Problem",
                    Object.values(error.response.data.errors)
                );
            }
        })
        .finally(() => {
            loading_spinner.value = false;
        });
};

// Fetch permissions and role data when the component is mounted
onMounted(() => {
    getPermissions();
    getRoleData();
});
</script>

<template>
   <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">👈{{ $t("dashboard") }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-left">
                        <li class="breadcrumb-item">
                            <router-link to="/admin/dashboard">{{ $t("home") }}</router-link>
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-success col-8 offset-2">
        <div class="card-header">
            <h3 class="card-title">د قوانین اصلاح کول</h3>
        </div>
        <form @submit.prevent="submitForm">
            <div class="card-body">
                <div class="form-group col-lg-10 offset-1">
                    <label>د قوانین نوم<b style="color: red"> * </b></label>
                    <input
                        type="text"
                        v-model="formData.name"
                        autocomplete="off"
                        placeholder="نام قوانین را بنوسید"
                        class="form-control"
                        required
                        :class="{
                            'is-invalid': errors.name,
                        }"
                    />
                    <span class="invalid-feedback" v-if="errors && errors.name">{{ errors.name[0] }}</span>
                    <span class="invalid-feedback" v-if="errors && errors.permissions"><hr />{{ errors.permissions[0] }}</span>
                </div>
                <hr class="mb-4" />
                <div class="row col-lg-10 h1 offset-lg-1 text-danger">
                    <div v-for="permission in permissions" :key="permission.id" class="col-lg-3 col-md-3 pr-5" style="text-align: left">
                        <label>
                            <span class="text-sm">
                                <span class="mr-2">{{ permission.name }}</span>
                            </span>
                            <input type="checkbox" v-model="formData.permissions" :value="permission.name" />
                        </label>
                    </div>
                </div>
                <hr />
                <button type="submit" class="btn btn-success" :disabled="loading_spinner">
                    <div v-if="loading_spinner" class="spinner-border spinner-border-sm" role="status">
                        <span class="sr-only">loading_spinner...</span>
                    </div>
                    <span v-else> <i class="fa fa-plus-circle"></i> تغیر </span>
                </button>
                <button @click.prevent="cancelButton" class="btn btn-warning ml-3"> <i class="fa fa-user"></i> Back</button>
            </div>
        </form>
    </div>
</template>

<style scoped></style>
