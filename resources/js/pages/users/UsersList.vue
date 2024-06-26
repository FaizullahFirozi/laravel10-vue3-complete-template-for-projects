<script setup>
// import axios from "axios";
import { ref, onMounted, reactive, watch, computed } from "vue";
import { Form, Field, ErrorMessage } from "vee-validate";
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

// GETTING DATA FROM DATABASE
const getUsers = (page = 1, per_Page = perPage.value) => {
    axios
        .get(`/api/users?page=${page}&per_page=${per_Page}`)
        .then((response) => {
            users.value = response.data;
        });
};

// FOR SORTING ID
let sortOrder = "asc"; // Initial sort order

const sortBy = (field) => {
    if (field === "id" || field === "name") {
        if (Array.isArray(users.value.data)) {
            users.value.data.sort((a, b) => {
                const multiplier = sortOrder === "asc" ? 1 : -1;
                // For string fields like 'name', use localeCompare for sorting
                if (typeof a[field] === "string") {
                    return multiplier * a[field].localeCompare(b[field]);
                }
                // For numeric fields like 'id', use direct comparison
                return multiplier * (a[field] - b[field]);
            });
            sortOrder = sortOrder === "asc" ? "desc" : "asc";
        }
    }
};

// FRONTEND FORM VALIDATION FOR NEW ADD
const createUserSchema = yup.object({
    name: yup.string().required().min(3),
    last_name: yup.string().required().min(3).label("Last Name"),
    father_name: yup.string().required().min(3).label("Father Name"),
    dob: yup.string().required().label("Date Of Birth"),
    hire_date: yup.string().required().label("Hire Date"),
    nic: yup.string().required().label("National Identity"),
    gross_salary: yup.string().required().label("Gross Salary"),
    phone: yup.string().required().min(10),
    avatar: yup.string().nullable(),
    email: yup.string().email().required(),
    password: yup.string().required().min(5),
    retype_password: yup
        .string()
        .required()
        .min(5)
        .oneOf([yup.ref("password")], "Repty Password Not Match with Password")
        .label("Retype Password"),
});

// FRONTEND FORM VALIDATION FOR UPDATE
const editUserSchema = yup.object({
    name: yup.string().required().min(3),
    last_name: yup.string().required().min(3),
    father_name: yup.string().required().min(3),
    dob: yup.string().required(),
    hire_date: yup.string().required(),
    nic: yup.string().required(),
    gross_salary: yup.string().required().label("Gross Salary"),
    phone: yup.string().required().min(10),
    avatar: yup.string().required(),
    email: yup.string().email().required(),
    password: yup.string().min(5),
    retype_password: yup
        .string()
        .min(5)
        .oneOf([yup.ref("password")], "Repty Password Not Match with Password")
        .label("Retype Password"),
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
        })
        .catch((error) => {
            useToastrError(error.response.data.message);

            // setFieldError is for server vlidation response
            setFieldError("phone", error.response.data.errors.phone);
            setFieldError("email", error.response.data.errors.email);
            setFieldError("nic", error.response.data.errors.nic);
            setFieldError("dob", error.response.data.errors.dob);
            setFieldError("hire_date", error.response.data.errors.hire_date);

            if (error.response.status === 422) {
                useSweetAlert("مبارک شه", "د کارمند معلومات تعغیر شول");
            }
            if (error.response.status === 500) {
                useSweetAlert("مبارک شه", "د کارمند معلومات تعغیر شول");
            }
            if (error.response.status === 500) {
                useSweetAlert("مبارک شه", "د کارمند معلومات تعغیر شول");
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
                useToastrError("مشکل", "validation error");
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
        gross_salary: user.gross_salary,
        avatar: user.avatar,
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


const showTableRow = ref(true); //FOR SHOW EMPTY rows

const search = () => {
    showTableRow.value = true;

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
        })
        .finally(() => {
            setTimeout(() => {
                showTableRow.value = false;
            }, 1000);
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

    setTimeout(() => {
    showTableRow.value = false;
  }, 5000);
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
                                <th style="width: 10px" @click="sortBy('id')">
                                    ID <i class="fas fa-sort"></i>
                                </th>
                                <th @click="sortBy('name')">
                                    Name <i class="fas fa-sort"></i>
                                </th>
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
                                    <td>
                                        <img v-if="user.email === 'super_admin@gmail.com'  || user.email ===  'admin@gmail.com' || user.email ===  'user@gmail.com'"
                                            class="profile-user-img img-fluid img-circle img-md"
                                            :src="user.avatar"
                                        />
                                        <img v-else
                                            class="profile-user-img img-fluid img-circle img-md"
                                            :src="'/logo.png'"
                                        />
                                    </td>
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
                                <tr v-if="showTableRow">
                                    <td colspan="13" align="center">
                                        مهربانی وکړئ لږ انتظار شئ...
                                        <div class="spinner-border text-gray">
                                            <span class="sr-only"
                                                >Loading...</span
                                            >
                                        </div>
                                    </td>
                                </tr>
                                <tr v-else>
                                    <td colspan="13" align="center">
                                        معلومات پیدا نشول...
                                    </td>
                                </tr>
                            </tbody>
                        </transition>

                        <tfoot class="bg-secondary">
                            <tr>
                                <th style="width: 10px">ID</th>
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
                    <button
                        type="button"
                        class="close bg-danger"
                        data-dismiss="modal"
                    >
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
                                <ErrorMessage
                                    name="name"
                                    class="invalid-feedback"
                                    as="strong"
                                />
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
                                <ErrorMessage
                                    name="last_name"
                                    class="invalid-feedback"
                                />
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
                                <ErrorMessage
                                    name="father_name"
                                    class="invalid-feedback"
                                />
                            </div>
                        </div>
                        <div class="row col-lg-12">
                            <div class="form-group col-lg-4">
                                <label>Phone</label>
                                <Field
                                    name="phone"
                                    type="tel"
                                    class="form-control"
                                    :class="{ 'is-invalid': errors.phone }"
                                    placeholder="Enter Phone Number د اړیکی شمیره"
                                />
                                <ErrorMessage
                                    name="phone"
                                    class="invalid-feedback"
                                />
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
                                <ErrorMessage
                                    name="email"
                                    class="invalid-feedback"
                                />
                            </div>
                            <div class="form-group col-lg-4">
                                <label>Image</label>
                                <Field
                                    name="avatar"
                                    type="file"
                                    accept="image/*"
                                    class="form-control"
                                    :class="{ 'is-invalid': errors.avatar }"
                                />
                                <ErrorMessage
                                    name="avatar"
                                    class="invalid-feedback"
                                />
                            </div>
                        </div>
                    </div>
                    <div class="row col-lg-12">
                        <div class="form-group col-lg-4">
                            <label>Date Of B</label>
                            <Field name="dob" type="date" v-slot="{ field }">
                                <date-picker
                                    :column="1"
                                    mode="single"
                                    v-bind="field"
                                    format="YYYY"
                                    required
                                    class="no border"
                                    :class="{
                                        'border border-danger': errors.dob,
                                    }"
                                />
                            </Field>
                            <ErrorMessage
                                name="dob"
                                class="text-purple text-sm"
                            />
                        </div>
                        <div class="form-group col-lg-4">
                            <label>Hire Date</label>
                            <Field name="hire_date" v-slot="{ field }">
                                <date-picker
                                    :column="1"
                                    mode="single"
                                    v-bind="field"
                                    class="no border"
                                    :class="{
                                        'border border-danger':
                                            errors.hire_date,
                                    }"
                                />
                            </Field>
                            <ErrorMessage
                                name="hire_date"
                                class="text-pink text-sm"
                            />
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
                            <ErrorMessage name="nic" class="invalid-feedback" />
                        </div>
                    </div>

                    <div class="row col-lg-12">
                        <div class="form-group col-lg-4">
                            <label>Gross Salary</label>
                            <Field
                                name="gross_salary"
                                type="number"
                                class="form-control"
                                :class="{ 'is-invalid': errors.gross_salary }"
                                placeholder="Enter Gross Salary"
                            />
                            <ErrorMessage
                                name="gross_salary"
                                class="invalid-feedback"
                            />
                        </div>
                        <div class="form-group col-lg-4">
                            <label>Password</label>
                            <Field
                                name="password"
                                type="password"
                                rules="required"
                                class="form-control"
                                :class="{ 'is-invalid': errors.password }"
                                placeholder="Enter password"
                                autocomplete
                            />
                            <ErrorMessage
                                name="password"
                                class="invalid-feedback"
                            />
                        </div>

                        <div class="form-group col-lg-4">
                            <label>Retype Password</label>
                            <Field
                                name="retype_password"
                                type="password"
                                class="form-control"
                                :class="{
                                    'is-invalid': errors.retype_password,
                                }"
                                placeholder="Enter Retype password"
                                autocomplete
                            />
                            <ErrorMessage
                                name="retype_password"
                                class="invalid-feedback"
                            />
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


<style>
.profile-user-img:hover {
    background-color: rgb(43, 255, 0);
    cursor: pointer;
}
</style>

