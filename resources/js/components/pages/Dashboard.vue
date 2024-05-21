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
    <div class="content">
        <div class="container-fluid">
            <span>
                {{ $t("test_localizition") }}
                {{ $t("test_localizition") }}
            </span>
            <div class="row">
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Card title</h5>

                            <p class="card-text">
                                Some quick example text to build on the card
                                title and make up the bulk of the card's
                                content.
                            </p>

                            <a href="#" class="card-link">Card link</a>
                            <a href="#" class="card-link">Another link</a>
                        </div>
                    </div>
                    <div class="card card-primary card-outline">
                        <div class="card-body">
                            <h5 class="card-title">Card title</h5>

                            <p class="card-text">
                                Some quick example text to build on the card
                                title and make up the bulk of the card's
                                content.
                            </p>
                            <a href="#" class="card-link">Card link</a>
                            <a href="#" class="card-link">Another link</a>
                        </div>
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col-md-6 -->
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="m-0">Featured</h5>
                        </div>
                        <div class="card-body">
                            <h6 class="card-title">Special title treatment</h6>

                            <p class="card-text">
                                With supporting text below as a natural lead-in
                                to additional content.
                            </p>
                            <a href="#" class="btn btn-primary">Go somewhere</a>
                        </div>
                    </div>

                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h5 class="m-0">Featured</h5>
                        </div>
                        <div class="card-body">
                            <h6 class="card-title">Special title treatment</h6>

                            <p class="card-text">
                                With supporting text below as a natural lead-in
                                to additional content.
                            </p>
                            <a href="#" class="btn btn-primary">Go somewhere</a>
                        </div>
                    </div>
                </div>
                <div v-if="can('test-read')">
                    <!-- /.col-md-6 -->
                    <h1>{{ message }}</h1>
                    <h1>{{ name }}</h1>
                    <h1>{{ last }}</h1>
                </div>
                <hr />
                <h1 v-if="is('User' || 'Super Admin')">You are User</h1>
                <hr />
                <h1 v-if="is('Super Admin')">You are Super Admin <small class="text-danger">has some problem not show all</small></h1>
                <hr />
                <h1 v-if="is('Admin')">You are Admin</h1>
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </div>
    <!-- /.content -->
</template>

<script>
export default {
    data() {
        return {
            message: "this is by faizullah firozi wardak",
            name: "faizullah wardak",
            last: " firozi",
            province: "wardak",
        };
    },
};
</script>

<!-- <script>
    export default {
        data() {
            return {
                message: 'this is from some text'
            };
        }
    };
</script> -->
