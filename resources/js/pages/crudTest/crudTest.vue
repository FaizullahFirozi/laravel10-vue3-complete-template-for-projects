<script setup>
// import axios from "axios";
import { ref, onMounted, watch, computed } from "vue";
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

const crud_data = ref({ data: [] }); //THIS IS VUE MODEL DATA
const editing = ref(false);
const form = ref(null);

// BUTTON SPINNERS FOR TWO SECTIONS
const loading_spinner = ref(false);
const loading_spinner_submit = ref(false);

// GETTING DATA FROM DATABASE TO SHOW
const getCrudTest = (page = 1, per_Page = perPage.value) => {
    axios
        .get(`/api/crudtest?page=${page}&per_page=${per_Page}`)
        .then((response) => {
            crud_data.value = response.data;
        });
};


// FOR SORTING ID AND NAME IN TABLE
let sortOrder = "asc"; // Initial sort order

const sortBy = (field) => {
    if (field === "id" || field === "name") {
        if (Array.isArray(crud_data.value.data)) {
            crud_data.value.data.sort((a, b) => {
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
    phone: yup.number().required().min(10),
    email: yup.string().email().required(),
    description: yup.string().required(),
});

// FRONTEND FORM VALIDATION FOR UPDATE
const editUserSchema = yup.object({
    name: yup.string().required().min(3),
    phone: yup.string().required().min(10),
    email: yup.string().email().required(),
    description: yup.string().required(),
});

// STORING DATA TO DATABASE
const createUser = (values, { resetForm, setFieldError }) => {
    loading_spinner_submit.value = true;
    axios
        .post("/api/crudtest", values)
        .then((response) => {
            $("#userFormModal").modal("hide");
            crud_data.value.data.unshift(response.data);
            resetForm();

            useSweetAlert();
            useToastrSuccess();
        })
        .catch((error) => {
            setFieldError("phone", error.response.data.errors.phone);
            setFieldError("email", error.response.data.errors.email);
            setFieldError(
                "description",
                error.response.data.errors.description
            );

            if (error.response.status === 422) {
                //422 VALIDATION ERROR
                useToastrError();
            }
        })
        .finally(() => {
            loading_spinner_submit.value = false;
        });
};

// UPDATE DATA TO DATABASE***
const updateUser = (values, { setFieldError }) => {
    loading_spinner_submit.value = true;

    axios
        .put("/api/crudtest/" + values.id, values)
        .then((response) => {
            const index = crud_data.value.data.findIndex(
                (user) => user.id === response.data.id
            );
            crud_data.value.data[index] = response.data;
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
        phone: user.phone,
        email: user.email,
        description: user.description,
    });
};

// TO SHOW ADD MODAL OR EDIT***
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
            axios.delete(`/api/crudtest/${id}`).then((response) => {
                crud_data.value.data = crud_data.value.data.filter(
                    (user) => user.id !== id
                );
                // getUsers(); // FOR TOTAL COUNT AGAIN

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
        .get("/api/crudtest/search", {
            params: {
                query: searchQuery.value,
                per_page: perPage.value,
            },
        })
        .then((response) => {
            crud_data.value = response.data;
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
    getCrudTest();
    
    setTimeout(() => {
    showTableRow.value = false;
  }, 5000);
});
</script>
<template>
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Starter Page</h1>
                </div>
                <!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-left">
                        <li class="breadcrumb-item">
                            <router-link to="/admin/dashboard"
                                >Home</router-link
                            >
                        </li>
                        <li class="breadcrumb-item active">Starter Page</li>
                    </ol>
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <div class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header bg-dafault pt-4">
                    <button
                        @click.prevent="addUser"
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
                <!-- /.card-header -->
                <div class="card-body">
                    <span class="float-left badge badge-pill badge-default">
                        ټول کارمندان <br /><br /><span class="text-success">
                            {{ crud_data.total }}</span
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
                    <table
                        class="table table-hover table-responsive-sm table-responsive-md"
                    >
                        <thead style="background-color: #4682b4">
                            <tr>
                                <!-- <th style="width: 10px">#</th> -->
                                <th @click="sortBy('id')">
                                    ID <i class="fas fa-sort"></i>
                                </th>
                                <th @click="sortBy('name')">
                                    Name <i class="fas fa-sort"></i>
                                </th>
                                <th>Company Email</th>
                                <th>Phone</th>
                                <th>Description</th>
                                <th>Updated At</th>
                                <th>Options</th>
                            </tr>
                        </thead>
                        <transition name="slide-down-fade">
                            <tbody v-if="crud_data.data.length > 0">
                                <tr
                                    v-for="item in crud_data.data"
                                    :key="item.id"
                                >
                                    <!-- <tr
                                v-for="(item, index) in crud_data"
                                :key="item.id"
                            > -->
                                    <!-- <td v-text="index + 1"></td> -->
                                    <!-- slow -->
                                    <td>{{ item.id }}</td>
                                    <!-- but so fast -->
                                    <td v-text="item.name"></td>
                                    <td v-text="item.email"></td>
                                    <td v-text="item.phone"></td>
                                    <td v-text="item.description"></td>
                                    <td v-text="item.created_at"></td>
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
                                                        editUser(item)
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
                                                            item.id
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
                                    <td colspan="7" align="center">
                                        مهربانی وکړئ لږ انتظار شئ...
                                        <div class="spinner-border text-gray">
                                            <span class="sr-only"
                                                >Loading...</span
                                            >
                                        </div>
                                    </td>
                                </tr>
                                <tr v-else>
                                    <td colspan="7" align="center">
                                        معلومات پیدا نشول...
                                    </td>
                                </tr>
                            </tbody>
                        </transition>

                        <tfoot class="bg-secondary">
                            <tr>
                                <!-- <th style="width: 10px">#</th> -->
                                <th>ID</th>
                                <th>Name</th>
                                <th>Company Email</th>
                                <th>Phone</th>
                                <th>Description</th>
                                <th>Updated At</th>
                                <th>Options</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div>
                    <div class="float-left pr-5 pt-2">
                        Showing {{ crud_data.from }} to {{ crud_data.to }} of
                        {{ crud_data.total }} entries
                    </div>
                    <Bootstrap4Pagination
                        size="default"
                        :show-disabled="true"
                        :limit="2"
                        :keepLength="true"
                        :data="crud_data"
                        @pagination-change-page="getCrudTest"
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

                            <Field name="password" v-slot="{ field }">
                                <!-- <input v-bind="field" type="password" /> -->
                                <date-picker
                                    v-bind="field"
                                    :modal="true"
                                    color="purple"
                                    :column="1"
                                    mode="single"
                                    placeholder="تاریخ تولد"
                                ></date-picker>
                            </Field>
                            <date-picker
                                :modal="true"
                                color="purple"
                                :column="1"
                                mode="single"
                                placeholder="az تاریخ تولد"
                            ></date-picker>
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
                                <label>Description:</label>
                                <Field
                                    name="description"
                                    type="text"
                                    class="form-control"
                                    :class="{
                                        'is-invalid': errors.description,
                                    }"
                                    placeholder="Enter Description"
                                />
                                <span class="invalid-feedback">{{
                                    errors.description
                                }}</span>
                            </div>
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
