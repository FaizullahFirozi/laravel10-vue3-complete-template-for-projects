<script setup>
import axios from "axios";
import { ref, onMounted } from "vue";

const activity_logs = ref([]);

// GETTING DATA FROM DATABASE
const fetchActivityLog = () => {
    axios.get("/api/activity_log").then((response) => {
        activity_logs.value = response.data;
    });
};

onMounted(() => {
    fetchActivityLog();
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
            <span class=" badge badge-pill text-md badge-warning">ټول لاګونه: <span class=" badge badge-pill text-md badge-success">{{ activity_logs.length }}</span></span>
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
                    <table
                        class="table table-borderless table-bordered table-responsive-sm table-responsive-md table-responsive-lg"
                    >
                        <thead class="bg-secondary">
                            <tr>
                                <th style="width: 10px">#</th>
                                <th>Name</th>
                                <th>Log Name</th>
                                <th>DATE</th>
                                <th class="text-left">New Data</th>
                                <th class="text-left">Old Data</th>
                            </tr>
                        </thead>
                        <tbody v-if="activity_logs.length > 0">
                            <tr
                                v-for="items in activity_logs"
                                :key="items.id"
                            >
                                <td>{{ items.id }}</td>
                                <td>{{ items.log_name }}</td>
                                <td>{{ items.description }}</td>
                                <td>{{ formatDate(items.updated_at) }}</td>

                                <td
                                    dir="ltr"
                                    class="text-left text-xs border"
                                    style="max-width: 350px"
                                >
                                    <div class="border">
                                        <span class="badge badge-success"
                                            >New Data:</span
                                        >
                                        {{ items.properties.attributes }}
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
                                        {{ items.properties.old }}
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                        <tbody v-else>
                            <tr>
                                <td colspan="13" align="center">
                                    مهربانی وکړئ لږ انتظار شئ...
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>
