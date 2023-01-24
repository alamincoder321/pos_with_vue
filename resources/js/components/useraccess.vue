<template>
    <div class="container-fluid px-4">
        <h1 class="mt-4">User Access</h1>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <form @submit.prevent="savePermission">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div class="form-group">
                                    <input type="checkbox" @change="allCheck" id="all"> <label for="all">All</label>
                                </div>
                                <div class="form-group">
                                    <router-link to="/users" class="btn btn-outline-danger shadow-none">Back User
                                    </router-link>
                                    <button :style="{display: checkUserPermission.includes('useraccess.store')?'':'none'}" type="submit" class="btn btn-outline-success shadow-none">Give
                                        Permission</button>
                                </div>
                            </div>
                            <hr>
                            <div class="row" v-for="(item, index) in permissions" :key="index">
                                <div class="col-4">
                                    <input @change="groupChecked" type="checkbox" :value="item.group_name"
                                        :id="'group-' + item.id"> <label :for="'group-' + item.id">{{
                                                item.group_name.charAt().toUpperCase() + item.group_name.slice(1)
                                        }}</label>
                                </div>
                                <div class="col-8" :class="'checked-' + item.group_name">
                                    <span v-for="per in groupName(item.group_name)" :key="per.id">
                                        <input type="checkbox" :checked="alluserPermision.includes(per.permission)"
                                            name="permission[]" class="permission_name" :value="per.id"
                                            :id="'permission-' + per.id">
                                        <label :for="'permission-' + per.id">{{ per.permission }}</label><br />
                                    </span>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            permissions: [],
            useraccess: [],
            alluserPermision: [],
            checkUserPermission: [],
            user_id: null,
        };
    },
    created() {
        this.user_id = localStorage.getItem("user_id");
        this.getUserAccess();
        this.getPermission();
        this.getCheckPermission();
        this.logOut();
    },
    methods: {
        getUserAccess() {
            axios.get("/api/get-useraccess").then((res) => {
                this.permissions = res.data.group;
                this.useraccess = res.data.permission;
            });
        },
        savePermission(event) {
            if ($(".permission_name").is(':checked')) {
                let formdata = new FormData(event.target)
                formdata.append("user_id", this.$route.params.id)
                axios.post("/api/save-permission", formdata).then((res) => {
                    this.$toastr.s(res.data, "Success!");
                    this.$router.push({ path: "/users" });
                    
                });
            } else {
                alert("Must be checked permission")
            }
        },

        getPermission() {
            axios.get("/api/get-permission/" + this.$route.params.id).then((res) => {
                this.alluserPermision = Array.from(res.data);
            });
        },
        groupName(name) {
            return this.useraccess.filter(per => per.group_name == name)
        },
        allCheck(event) {
            if (event.target.checked) {
                $("[type='checkbox']").prop("checked", true);
            } else {
                $("[type='checkbox']").prop("checked", false);
            }
        },
        groupChecked(event) {
            var className = $(".checked-" + event.target.value + " [type='checkbox']")
            if (event.target.checked) {
                className.prop("checked", true)
            } else {
                className.prop("checked", false)
            }
        },
        getCheckPermission(){
            axios.get("/api/get-permission/" + this.user_id).then((res) => {
                this.checkUserPermission = Array.from(res.data);
            });
        },
        logOut() {
            if (this.user_id === null) {
                axios.get(location.origin + "/logout").then((res) => {
                    alert("Logout");
                    location.reload();
                });
            }
        },
    },
    watch: {
        checkUserPermission(){
            this.checkUserPermission.includes("useraccess.index")?"":location.href = "/unauthorize"
        }
    }, 
    mounted() {
        document.title = "User Access Page";
    },
};
</script>
