<template>
    <div class="container-fluid mt-2">
        <div class="row">
            <div class="col-12 col-lg-12">
                <div class="card">
                    <div class="card-header position-relative"
                        style="background:linear-gradient(45deg, #bb3a87, #000000d1);">
                        <div class="row">
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <v-select :options="employers" id="employee" v-model="selectedEmployee"
                                        label="name"></v-select>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <input type="month" v-model="monthName" :min="minMonth" class="form-control"/>
                                </div>
                            </div>
                            
                            <div class="col-lg-1">
                                <button type="button" @click="getSalary"
                                    class="btn btn-info btn-sm text-white shadow-none px-3">
                                    Submit
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body" v-if="salaries.length > 0">

                    </div>
                    <div class="card-body text-center" v-else>
                        Not found data in Table
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
var moment = require('moment');
export default {

    data() {
        return {
            minMonth: moment().format("YYYY-MM"),
            monthName: moment().format("YYYY-MM"),
            employers: [],
            selectedEmployee: null,
            salaries: [],
            useraccess: [],
            user_id: null,
        }
    },
    created() {
        this.user_id = localStorage.getItem("user_id");
        this.getPermission();
        this.getEmployee()
        this.logOut();
    },
    methods: {
        getEmployee() {
            axios.get("/api/get-employer").then((res) => {
                this.employers = res.data.employers;
                this.employers.unshift({id: "", name: "All"})
            });
        },

        getSalary(){
            if(this.selectedEmployee == null ){
                alert("Select employee first")
                return
            }

            let data = {
                employerId: this.selectedEmployee.id,
                monthName : this.monthName,
                added_by  : this.user_id
            }
            axios.post("/api/salary-generate", data)
                .then(res => {
                    console.log(res.data)
                })
        },

        getPermission() {
            axios.get("/api/get-permission/" + this.user_id).then((res) => {
                this.useraccess = Array.from(res.data);
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
        useraccess() {
            this.useraccess.includes("dashboard") ? "" : location.href = "/unauthorize"
        }
    },
    mounted() {
        document.title = "Salary Generate"
    },
};
</script>
<style>
#employee{
    width: 100% !important;
}
</style>