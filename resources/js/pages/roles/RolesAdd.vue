<script setup>
import axios from "axios";
import { ref, onMounted, reactive } from "vue";
import { useSweetAlert, useToastrError } from "@/components/toastr.js";

// FOR REDIRICT WHEN SAVE
import { useRouter } from "vue-router";
const router = useRouter();
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

// ADDING NOTIFICATION MESSAGE...
const playNotificationSound = () => {
    const audio = new Audio("/notifi_sound.wav");
    audio.play();
};
const cancelButton = () => {
    goBack();
}

const submitForm = () => {
    errors.value = "";
    loading_spinner.value = true;

    axios
        .post("/api/roles", formData)
        .then((response) => {
            playNotificationSound();
            useSweetAlert("مبارک شه", "نوی قوانین اضافه شول");
            goBack(); // GO BACK
        })
        .catch((error) => {
            if (error.response && error.response.status === 422) {
                errors.value = error.response.data.errors;
                useToastrError(
                    "Validation Problem",
                    Object.values(error.response.data.errors)
                );
            } else if (error.response && error.response.status === 500) {
                useToastrError(
                    "Alredy Axsist Problem", "په دی نوم قوانین مخکی موجود دی"
                );  

            }
        })
        .finally(() => {
            loading_spinner.value = false;
            formData.reset();
        });
};

onMounted(() => {
    getPermissions();
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

    <div class="card card-orange col-8 offset-2">
        <div class="card-header">
            <h3 class="card-title">د قوانین اضافه کول</h3>
        </div>
        <!-- form start -->
        <!-- form start -->
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
                    <span
                        class="invalid-feedback"
                        v-if="errors && errors.name"
                        >{{ errors.name[0] }}</span
                    >

                    <span
                        class="invalid-feedback"
                        v-if="errors && errors.permissions"
                        ><hr />
                        {{ errors.permissions[0] }}</span
                    >
                </div>

                <hr class="mb-4" />

                <div class="row col-lg-10 h1 offset-lg-1 text-danger">
                    <div
                        v-for="permission in permissions"
                        :key="permission.id"
                        class="col-lg-3 col-md-3 pr-5"
                        style="text-align: left"
                    >
                        <label>
                            <span class="text-sm">
                                <span class="mr-2">{{
                                    permission.name
                                }}</span></span
                            >
                            <input
                                type="checkbox"
                                v-model="formData.permissions"
                                :value="permission.name"
                            />
                        </label>
                    </div>
                </div>
                <hr />
                <button
                    type="submit" 
                    class="btn btn-success"
                    :disabled="loading_spinner"
                >
                    <div
                        v-if="loading_spinner"
                        class="spinner-border spinner-border-sm"
                        role="status"
                    >
                        <span class="sr-only">loading_spinner...</span>
                    </div>
                    <span v-else> <i class="fa fa-plus-circle"></i> ثبت </span>
                </button>
                <button @click.prevent="cancelButton" class="btn btn-warning ml-3" > <i class="fa fa-user"></i> Back</button>
            </div>
        </form>
    </div>
</template>

<style scoped></style>
