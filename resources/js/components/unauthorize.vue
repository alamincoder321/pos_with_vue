<template>
    <div class="container-fluid px-4 mt-4">
        <div class="row">
            <div class="col-xl-12 col-md-12">
                <div class="card bg-warning text-white mb-4">
                    <div class="card-body"><h1>UnAuthorized Access</h1></div>
                    <div
                        class="card-footer d-flex align-items-center justify-content-between"
                    >
                        <a class="small text-white stretched-link"
                            >Opps! Very Sad.</a
                        >
                        <div class="small text-white">
                            <i class="fas fa-angle-right"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    mounted() {
        document.title = "UnAuthorized User Access"
    },
};
</script>
