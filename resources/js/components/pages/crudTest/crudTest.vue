<script setup>
import axios from "axios";
import { Bootstrap4Pagination } from "laravel-vue-pagination";


import { ref, onMounted } from "vue";
const crud_data = ref([]); //this is vue model

const getCrudTest = () => {
    axios.get("/api/crudtest").then((response) => {
        crud_data.value = response.data;
    });
};

onMounted(() => {
    getCrudTest();
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
                <div
                    class="card-header p-4"
                    style="background-color: #007bff30"
                >
                    <h3 class="card-title p-2 float-left">
                        د معلومات لیست / لیست معلومات ها
                    </h3>
                    <button
                        type="submit"
                        class="btn btn-outline-primary"
                        data-toggle="modal"
                        data-target="#addModal"
                    >
                        <i class="fa fa-plus"></i> اضافه کردن معلومات
                    </button>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                                       <table class="table table-hover">
                        <thead style="background-color: #4682b4">
                            <tr>
                                <!-- <th style="width: 10px">#</th> -->
                                <th>Company Name</th>
                                <th>Company Email</th>
                            </tr>
                        </thead>
                        <tbody v-if="crud_data.length > 0">
                            <tr
                                v-for="item in crud_data"
                                :key="item.id"
                            >
                            <!-- <tr
                                v-for="(item, index) in crud_data"
                                :key="item.id"
                            > -->
                                <!-- <td v-text="index + 1"></td> -->
                                <!-- slow -->
                                <td>{{ item.id }} atal</td>
                                <!-- but so fast -->
                                <td v-text="item.name"></td>
                                <!-- Display other country properties -->
                            </tr>
                        </tbody>
                        <tbody v-else>
                            <tr>
                                <td colspan="6" align="center">
                                    Company Inforamtion Not Found. معلومات پیدا
                                    نه شول
                                </td>
                                <td></td>
                            </tr>
                        </tbody>
                        <tfoot class="bg-secondary">
                            <tr>
                                <!-- <th  style="width: 10px">#</th> -->
                                <th>Company Name</th>
                                <th>Company Email</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>