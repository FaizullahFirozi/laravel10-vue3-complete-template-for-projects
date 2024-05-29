<script setup>
// import axios from "axios";
import { ref, onMounted, reactive, watch, computed } from "vue";
import { Form, Field } from "vee-validate";
import * as yup from "yup";

import {
    useToastrSuccess,
    useToastrError,
    useSweetAlert,
} from "@/components/toastr";
import Swal from "sweetalert2";

// ADDED FOR SEARCH TO NOT SEND REAQUES IN EVERY BUTTON ENTERD
import { debounce } from "lodash";
import { Bootstrap4Pagination } from "laravel-vue-pagination";

const users = ref({ data: [] });
const editing = ref(false);
const form = ref(null);

const loading_spinner = ref(false);
const loading_spinner_submit = ref(false);

const userIdBeingDeleted = ref(null);

// GETTING DATA FROM DATABASE
const getUsers = (page = 1, per_Page = perPage.value) => {
    axios
        .get(`/api/users?page=${page}&per_page=${per_Page}`)
        .then((response) => {
            users.value = response.data;
        });
};

// FRONTEND FORM VALIDATION FOR NEW ADD
const createUserSchema = yup.object({
    name: yup.string().required().min(3),
    last_name: yup.string().required().min(3),
    father_name: yup.string().required().min(3),
    dob: yup.string().required(),
    hire_date: yup.string().required(),
    nic: yup.string().required(),
    phone: yup.string().required().min(10),
    photo: yup.string().nullable(),
    email: yup.string().email().required(),
    password: yup.string().required().min(5),
    retype_password: yup.string().required().min(5),
});

// FRONTEND FORM VALIDATION FOR UPDATE
const editUserSchema = yup.object({
    name: yup.string().required().min(3),
    last_name: yup.string().required().min(3),
    father_name: yup.string().required().min(3),
    dob: yup.string().required(),
    hire_date: yup.string().required(),
    nic: yup.string().required(),
    phone: yup.string().required().min(10),
    photo: yup.string().required(),
    email: yup.string().email().required(),
    // password: yup.string().required().min(5),
    // retype_password: yup.string().required().min(5),
    // password: yup.string().when((password, schema) => {
    //     return password ? schema.required().min(8) : schema;
    // })
});

// STORING DATA TO DATABASE
const createUser = (values, { resetForm, setFieldError }) => {
    loading_spinner_submit.value = true;
    axios
        .post("/api/users", values)
        .then((response) => {
            useSweetAlert();
            useToastrSuccess();

            $("#userFormModal").modal("hide");
            users.value.data.unshift(response.data);
            resetForm();
            // toastr.success("Added Successfull", "Success");
        })
        .catch((error) => {
            setFieldError("phone", error.response.data.errors.phone);
            setFieldError("email", error.response.data.errors.email);
            setFieldError("nic", error.response.data.errors.nic);

            if (error.response.status === 422) {
                // toastr.error("validation error", "مشکل");
            }
        })
        .finally(() => {
            loading_spinner_submit.value = false;
        });
};

// UPDATE DATA TO DATABASE
const updateUser = (values, { setFieldError }) => {
    loading_spinner_submit.value = true;

    axios
        .put("/api/users/" + values.id, values)
        .then((response) => {
            const index = users.value.data.findIndex(
                (user) => user.id === response.data.id
            );
            users.value.data[index] = response.data;
            $("#userFormModal").modal("hide");
            useSweetAlert("مبارک شه", "د کارمند معلومات تعغیر شول");
        })
        .catch((error) => {
            setFieldError("phone", error.response.data.errors.phone);
            setFieldError("email", error.response.data.errors.email);

            if (error.response.status === 422) {
                // useToastrError("مشکل", "validation error");
            }
        })
        .finally(() => {
            loading_spinner_submit.value = false;
        });
};

// SHOW MODAL FOR ADDING
const addUser = () => {
    loading_spinner.value = true;

    editing.value = false;
    form.value.resetForm();
    $("#userFormModal").modal("show");

    setTimeout(() => {
        loading_spinner.value = false;
    }, 500);
};

// SHOW MODAL FOR EDITING
const editUser = (user) => {
    editing.value = true;
    // form.value.resetForm();
    $("#userFormModal").modal("show");

    form.value.setValues({
        id: user.id,
        name: user.name,
        last_name: user.last_name,
        father_name: user.father_name,
        hire_date: user.hire_date,
        dob: user.dob,
        nic: user.nic,
        phone: user.phone,
        photo: user.photo,
        email: user.email,
    });
};

// TO SHOW ADD MODAL OR UPDATE
const handleSubmit = (values, actions) => {
    // console.log(actions);
    if (editing.value) {
        updateUser(values, actions);
    } else {
        createUser(values, actions);
    }
};

const confirmUserDeletion = (id) => {
    Swal.fire({
        title: "آیا مطمئن یی؟",
        text: "دایم لپاره ډیلیټ کیږی",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "هو Yes",
        cancelButtonText: "نه No",
    }).then((result) => {
        if (result.isConfirmed) {
            axios.delete(`/api/users/${id}`).then((response) => {
                users.value.data = users.value.data.filter(
                    (user) => user.id !== id
                );
                // getUsers(); // FOR TOTAL COUNT AGAIN

                // Swal.fire({
                //     title: "حذف شو!",
                //     text: "ستاسو کارمند حذف شو!",
                //     icon: "success",
                // });
                useSweetAlert("حذف شو!");
            });
        }
    });
};

// FOR SEARCH
const searchQuery = ref(null);
const perPage = ref(5);

// FOR CHANGE SEARCH ICON 
const searchIcon = computed(() => {
    return searchQuery.value ? "fas fa-times" : "fas fa-search";
});

// FOR CLEAR SEARCHBAR
const clearSearch = () => {
    if (searchQuery.value) {
        searchQuery.value = "";
    } 
};

const search = () => {
    axios
        .get("/api/users/search", {
            params: {
                query: searchQuery.value,
                per_page: perPage.value,
            },
        })
        .then((response) => {
            users.value = response.data;
        })
        .catch((error) => {
            console.log(error);
        });
};

watch(
    searchQuery,
    debounce(() => {
        search();
    }, 500)
);

watch(perPage, () => {
    search();
});

onMounted(() => {
    getUsers();
});
</script>

<template>
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">ListUsers</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-left">
                        <li class="breadcrumb-item">
                            <router-link to="/admin/dashboard"
                                >Home</router-link
                            >
                        </li>
                        <li class="breadcrumb-item active">د کارکوونکو لیست</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header bg-dafault pt-4">
                    <button
                        @click="addUser"
                        type="button"
                        class="mb-2 btn btn-primary"
                        :disabled="loading_spinner"
                        v-if="is('Super Admin')"
                    >
                        <div
                            v-if="loading_spinner"
                            class="spinner-border spinner-border-sm"
                            role="status"
                        >
                            <span class="sr-only">loading_spinner...</span>
                        </div>

                        <span v-else
                            ><i class="fa fa-plus-circle mr-1"></i> Add New User
                        </span>
                    </button>
                    <h3 class="card-title float-left">د کارکوونکو لیست</h3>
                </div>
                <div class="card-body">
                    <span class="float-left badge badge-pill badge-default">
                        ټول کارمندان <br /><br /><span class="text-success">
                            {{ users.total }}</span
                        ></span
                    >

                    <select
                        v-model="perPage"
                        dir="ltr"
                        class="form-control form-control-sm float-left"
                        style="max-width: 65px"
                    >
                        <option value="5">5</option>
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                        <option value="1000000">All</option>
                    </select>

                    <div
                        class="input-group input-group-md pb-3"
                        style="width: 200px"
                    >
                        <input
                            type="text"
                            v-model="searchQuery"
                            class="form-control text-center float-right"
                            placeholder="لټون ..."
                        />

                        <div class="input-group-append">
                            <button
                                class="btn btn-default"
                                @click="clearSearch"
                            >
                                <i :class="searchIcon"></i>
                            </button>
                        </div>
                    </div>

                    <!-- <br /> -->

                    <!-- <table class="table table-borderless table-bordered table-responsive-sm table-responsive-md table-responsive-lg"> -->
                    <table
                        class="table table-hover table-responsive-sm table-responsive-md"
                    >
                        <thead class="bg-secondary">
                            <tr>
                                <th style="width: 10px">#</th>
                                <th>Name</th>
                                <th>Last Name</th>
                                <th>Father Name</th>
                                <th>DOB</th>
                                <th>NIC</th>
                                <th>Hire Date</th>
                                <th>Salary</th>
                                <th>phone</th>
                                <th>Image</th>
                                <th>Account</th>
                                <th>Email</th>
                                <th>Options</th>
                            </tr>
                        </thead>
                        <transition name="slide-down-fade">
                            <tbody v-if="users.data.length > 0">
                                <tr v-for="user in users.data" :key="user.id">
                                    <td>{{ user.id }}</td>
                                    <td>{{ user.name }}</td>
                                    <td>{{ user.last_name }}</td>
                                    <td>{{ user.father_name }}</td>
                                    <td>{{ user.dob }}</td>
                                    <td>{{ user.nic }}</td>
                                    <td>{{ user.hire_date }}</td>
                                    <td>{{ user.gross_salary }}</td>
                                    <td>{{ user.phone }}</td>
                                    <td>{{ user.photo }}</td>
                                    <td>{{ user.account_status }}</td>
                                    <td>{{ user.email }}</td>
                                    <td>
                                        <div class="btn-group" dir="ltr">
                                            <button
                                                type="button"
                                                class="btn btn-dafault text-danger dropdown-toggle dropdown-hover dropdown-icon"
                                                data-toggle="dropdown"
                                                aria-expanded="false"
                                            >
                                                <span class="sr-only"
                                                    >Toggle Dropdown</span
                                                >
                                            </button>
                                            <div
                                                class="dropdown-menu"
                                                role="menu"
                                            >
                                                <a
                                                    class="dropdown-item"
                                                    href="#"
                                                    @click.prevent="
                                                        editUser(user)
                                                    "
                                                    >تغیر
                                                    <i
                                                        class="fa fa-edit text-success ml-4"
                                                    ></i>
                                                </a>

                                                <a
                                                    class="dropdown-item"
                                                    href="#"
                                                    @click.prevent="
                                                        confirmUserDeletion(
                                                            user.id
                                                        )
                                                    "
                                                    >حذف
                                                    <i
                                                        class="fa fa-trash text-danger ml-4"
                                                    ></i>
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                            <tbody v-else>
                                <tr>
                                    <td colspan="13" align="center">
                                        معلومات پيدا نشول..!
                                    </td>
                                </tr>
                            </tbody>
                        </transition>

                        <tfoot class="bg-secondary">
                            <tr>
                                <th style="width: 10px">#</th>
                                <th>Name</th>
                                <th>Last Name</th>
                                <th>Father Name</th>
                                <th>DOB</th>
                                <th>NIC</th>
                                <th>Hire Date</th>
                                <th>Salary</th>
                                <th>phone</th>
                                <th>Image</th>
                                <th>Account</th>
                                <th>Email</th>
                                <th>Options</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div>
                    <div class="float-left pr-5 pt-2">
                        Showing {{ users.from }} to {{ users.to }} of
                        {{ users.total }} entries
                    </div>
                    <Bootstrap4Pagination
                        size="default"
                        :show-disabled="true"
                        :limit="2"
                        :keepLength="true"
                        :data="users"
                        @pagination-change-page="getUsers"
                        class="pl-5"
                    />
                </div>
            </div>
        </div>
    </div>

    <!-- The Create & Edit Modal -->
    <div
        class="modal fade"
        id="userFormModal"
        data-backdrop="static"
        data-keyboard="false"
        tabindex="-1"
        aria-labelledby="staticBackdropLabel"
        aria-hidden="true"
    >
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <!-- Modal Header -->
                <div
                    class="modal-header"
                    v-bind:class="{
                        'bg-success': editing,
                        'bg-primary': !editing,
                    }"
                >
                    <button type="button" class="close" data-dismiss="modal">
                        &times;
                    </button>
                    <h4 class="modal-title">
                        <span v-if="editing">Edit User</span>
                        <span v-else>Add New User</span>
                    </h4>
                </div>

                <!-- Modal body -->
                <!-- @submit="editing ? updateUser : createUser" -->

                <Form
                    ref="form"
                    @submit="handleSubmit"
                    :validation-schema="
                        editing ? editUserSchema : createUserSchema
                    "
                    v-slot="{ errors }"
                >
                    <div class="modal-body">
                        <div class="row">
                            <div class="form-group col-lg-4">
                                <label>First Name</label>
                                <Field
                                    name="name"
                                    type="text"
                                    class="form-control"
                                    :class="{ 'is-invalid': errors.name }"
                                    placeholder="Enter Name نوم"
                                />
                                <span class="invalid-feedback">{{
                                    errors.name
                                }}</span>
                            </div>
                            <div class="form-group col-lg-4">
                                <label>Last Name</label>
                                <Field
                                    name="last_name"
                                    type="text"
                                    class="form-control"
                                    :class="{ 'is-invalid': errors.last_name }"
                                    placeholder="Enter Last Name تخلص"
                                />
                                <span class="invalid-feedback">{{
                                    errors.last_name
                                }}</span>
                            </div>
                            <div class="form-group col-lg-4">
                                <label>Father Name</label>
                                <Field
                                    name="father_name"
                                    type="text"
                                    class="form-control"
                                    :class="{
                                        'is-invalid': errors.father_name,
                                    }"
                                    placeholder="Enter Father Name پلار نوم"
                                />
                                <span class="invalid-feedback">{{
                                    errors.father_name
                                }}</span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-lg-4">
                                <label>Phone</label>
                                <Field
                                    name="phone"
                                    type="tel"
                                    class="form-control"
                                    :class="{ 'is-invalid': errors.phone }"
                                    placeholder="Enter Phone Number د اړیکی شمیره"
                                />
                                <span class="invalid-feedback">{{
                                    errors.phone
                                }}</span>
                            </div>
                            <div class="form-group col-lg-4">
                                <label>Email:</label>
                                <Field
                                    name="email"
                                    type="email"
                                    class="form-control"
                                    :class="{ 'is-invalid': errors.email }"
                                    placeholder="Enter email ایمیل"
                                />
                                <span class="invalid-feedback">{{
                                    errors.email
                                }}</span>
                            </div>
                            <div class="form-group col-lg-4">
                                <label>Image</label>
                                <Field
                                    name="photo"
                                    type="file"
                                    accept="image/*"
                                    class="form-control"
                                    :class="{ 'is-invalid': errors.photo }"
                                />
                                <span class="invalid-feedback">{{
                                    errors.photo
                                }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Password</label>
                            <Field
                                name="password"
                                type="password"
                                class="form-control"
                                :class="{ 'is-invalid': errors.password }"
                                placeholder="Enter password"
                            />
                            <span class="invalid-feedback">{{
                                errors.password
                            }}</span>
                        </div>
                        <div class="form-group">
                            <label>Retype Password</label>
                            <Field
                                name="retype_password"
                                type="password"
                                class="form-control"
                                :class="{
                                    'is-invalid': errors.retype_password,
                                }"
                                placeholder="Enter Retype password"
                            />
                            <span class="invalid-feedback">{{
                                errors.retype_password
                            }}</span>
                        </div>
                    </div>
                    <div class="row col-lg-12">
                        <div class="form-group col-lg-4">
                            <label>Date Of B</label>
                            <Field
                                name="dob"
                                type="text"
                                class="form-control"
                                :class="{ 'is-invalid': errors.dob }"
                            />
                            <span class="invalid-feedback">{{
                                errors.dob
                            }}</span>
                        </div>
                        <div class="form-group col-lg-4">
                            <label>Hire Date</label>
                            <Field
                                name="hire_date"
                                type="date"
                                class="form-control"
                                :class="{ 'is-invalid': errors.hire_date }"
                            />
                            <span class="invalid-feedback">{{
                                errors.hire_date
                            }}</span>
                        </div>
                        <div class="form-group col-lg-4">
                            <label>NIC تذکره</label>
                            <Field
                                name="nic"
                                type="number"
                                class="form-control"
                                :class="{ 'is-invalid': errors.nic }"
                                placeholder="نمبر تذکره"
                            />
                            <span class="invalid-feedback">{{
                                errors.nic
                            }}</span>
                        </div>
                    </div>

                    <!-- Modal footer -->
                    <div class="modal-footer float-right">
                        <button
                            :disabled="loading_spinner_submit"
                            type="submit"
                            class="btn"
                            v-bind:class="{
                                'bg-success': editing,
                                'bg-primary': !editing,
                            }"
                        >
                            <div
                                v-if="loading_spinner_submit"
                                class="spinner-border spinner-border-sm"
                                role="status"
                            >
                                <span class="sr-only"
                                    >loading_spinner_submit...</span
                                >
                            </div>

                            <span v-else>
                                <span v-if="editing">Update</span>
                                <span v-else>Save</span>
                            </span>
                        </button>
                        <button
                            type="button"
                            class="btn btn-danger"
                            data-dismiss="modal"
                        >
                            Close
                        </button>
                    </div>
                </Form>
            </div>
        </div>
    </div>

    <!-- The Delete Modal -->
    <div
        class="modal fade"
        id="deleteUserModal"
        data-backdrop="static"
        data-keyboard="false"
        tabindex="-1"
        aria-labelledby="staticBackdropLabel"
        aria-hidden="true"
    >
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header bg-warning">
                    <h4 class="modal-title">Delete User</h4>
                    <button type="button" class="close" data-dismiss="modal">
                        &times;
                    </button>
                </div>
                <!-- Modal body -->
                <div class="modal-body">
                    <h6 class="modal-title">
                        Are you Sure want to Delete User ?
                    </h6>
                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <button
                        @click.prevent="deleteUser"
                        type="submit"
                        class="btn btn-danger pr-4 pl-4"
                    >
                        Yes
                    </button>

                    <button
                        type="button"
                        class="btn btn-secondary pr-5 pl-5"
                        data-dismiss="modal"
                    >
                        No
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<style>
/* .v-enter-from{
        opacity: 0;
    }
    .v-enter-to{
        opacity: 1;
    }
    .v-enter-active{
        transition: opacity 1s ease;
    } */

.slide-down-fade-enter-from {
    opacity: 0;
    transform: translateY(30px);
}
.slide-down-fade-enter-active {
    transition: all 1s ease;
}
</style>
