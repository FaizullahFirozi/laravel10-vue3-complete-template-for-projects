<script setup>
import axios from "axios";
import { ref, onMounted, watch, computed } from "vue";

// ADDED FOR SEARCH TO NOT SEND REAQUES IN EVERY BUTTON ENTERD
import { debounce } from "lodash";
import { Bootstrap4Pagination } from "laravel-vue-pagination";

const activity_logs = ref({ data: [] });

// GETTING DATA FROM DATABASE
const fetchActivityLog = (page = 1, per_Page = perPage.value) => {
    axios
        .get(`/api/activity_log?page=${page}&per_page=${per_Page}`)
        .then((response) => {
            activity_logs.value = response.data;
        });
};

// FOR SORTING SECTION
let sortOrder = "asc"; // Initial sort order
const sortBy = (field) => {
    if (field === "id" || field === "description") {
        if (Array.isArray(activity_logs.value.data)) {
            activity_logs.value.data.sort((a, b) => {
                const multiplier = sortOrder === "asc" ? 1 : -1;
                // For string fields like 'description', use localeCompare for sorting
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
        .get("/api/activity_log/search", {
            params: {
                query: searchQuery.value,
                per_page: perPage.value,
            },
        })
        .then((response) => {
            activity_logs.value = response.data;
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
    fetchActivityLog();

    setTimeout(() => {
        showTableRow.value = false;
    }, 5000);
});

// CREATED BY FRZ FOR ONLY SHOW 10 CARACTAR DATA
function formatDate(dateString) {
    return dateString.slice(0, 10);
}
</script>

<template>
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <span class="badge badge-pill text-md badge-warning"
                        >ټول لاګونه:
                        <span class="badge badge-pill text-md badge-success">{{
                            activity_logs.total
                        }}</span></span
                    >
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-left">
                        <li class="breadcrumb-item">
                            <router-link to="/admin/dashboard"
                                >Home</router-link
                            >
                        </li>
                        <li class="breadcrumb-item active">د لاګ لیست</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <select
                        v-model="perPage"
                        dir="ltr"
                        class="form-control form-control-sm float-left mr-3"
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
                    <table
                        class="table table-borderless table-bordered table-responsive-sm table-responsive-md table-responsive-lg"
                    >
                        <thead class="bg-secondary">
                            <tr>
                                <th style="width: 10px" @click="sortBy('id')">
                                    ID <i class="fas fa-sort"></i>
                                </th>
                                <th>User Name</th>
                                <th>Changes Table</th>
                                <th @click="sortBy('description')">
                                    Log Name <i class="fas fa-sort"></i>
                                </th>
                                <th>DATE</th>
                                <th class="text-left">New Data</th>
                                <th class="text-left">Old Data</th>
                            </tr>
                        </thead>
                        <transition name="slide-down-fade-frz">
                            <tbody v-if="activity_logs.data.length > 0">
                                <tr
                                    v-for="item in activity_logs.data"
                                    :key="item.id"
                                >
                                    <td>{{ item.id }}</td>
                                    <td>{{ item.causer_id }}</td>
                                    <td>{{ item.log_name }}</td>
                                    <td>{{ item.description }}</td>
                                    <td>{{ formatDate(item.updated_at) }}</td>

                                    <td
                                        dir="ltr"
                                        class="text-left text-xs border"
                                        style="max-width: 350px"
                                    >
                                        <div class="border">
                                            <span class="badge badge-success"
                                                >New Data:</span
                                            >
                                            {{ item.properties.attributes }}
                                        </div>
                                    </td>
                                    <td
                                        dir="ltr"
                                        class="text-left text-xs border"
                                        style="max-width: 350px"
                                    >
                                        <div class="border">
                                            <span class="badge badge-danger"
                                                >Old Data:</span
                                            >
                                            {{ item.properties.old }}
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
                    </table>
                </div>
                <div>
                    <div class="float-left pr-5 pt-2">
                        Showing {{ activity_logs.from }} to
                        {{ activity_logs.to }} of
                        {{ activity_logs.total }} entries
                    </div>
                    <Bootstrap4Pagination
                        size="default"
                        :show-disabled="true"
                        :limit="2"
                        :keepLength="true"
                        :data="activity_logs"
                        @pagination-change-page="fetchActivityLog"
                        class="pl-5"
                    />
                </div>
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
