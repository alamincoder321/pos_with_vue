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
                                    <input type="month" v-model="monthName" :min="minMonth" class="form-control" />
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <select class="form-select" v-model="status">
                                        <option value="p">Pending</option>
                                        <option value="d">Due</option>
                                    </select>
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
                        <template v-for="(salary, index) in salaries">
                            <table v-if="salary.salaryGenerate.length > 0" id="stocks" class="table table-sm table-hover table-bordered">
                                <thead style="background: #897800;color: white;">
                                    <tr>
                                        <th colspan="9" class="text-center font-weight-bold">{{ salary.name }} ({{
                                            salary.salaryGenerate.length
                                        }} days remaining)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="text-center">
                                        <th>Sl</th>
                                        <th>Payment Date</th>
                                        <th>Salary</th>
                                        <th>Due</th>
                                        <th>Advance</th>
                                        <th>OverTimeBonus</th>
                                        <th>LeaveDeduction</th>
                                        <th>Description</th>
                                        <th>Action</th>
                                    </tr>
                                    <tr v-for="(item, sl) in salary.salaryGenerate">
                                        <td style="width:5%;">{{ sl + 1 }}</td>
                                        <td style="width:15%;"><input
                                                :disabled="item.status == 'd' && item.date != null ? true : false"
                                                style="width:100%;" type="date" v-model="item.date"></td>
                                        <td style="width:13%;"><input style="width:100%;" type="number" min="0"
                                                step="0.01" v-model="item.salary"></td>
                                        <td style="width:11%;"><input style="width:100%;" type="number" min="0"
                                                step="0.01" v-model="item.due"></td>
                                        <td style="width:9%;"><input style="width:100%;" type="number" min="0"
                                                step="0.01" v-model="item.advance"></td>
                                        <td style="width:9%;"><input style="width:100%;" type="number" min="0"
                                                step="0.01" v-model="item.overTimeBonus"></td>
                                        <td style="width:9%;"><input style="width:100%;" type="number" min="0"
                                                step="0.01" v-model="item.leaveDeduction"></td>
                                        <td style="width:16%;"><input style="width:100%;" type="text"
                                                v-model="item.description"></td>
                                        <td style="width:13%;">
                                            <button :style="{ display: item.status == 'd' ? 'none' : '' }"
                                                @click="salaryDue(item)"
                                                class="btn btn-sm btn-danger shadow-none text-white">Due</button>
                                            <button @click="salaryPayment(item)"
                                                class="btn btn-sm btn-warning shadow-none text-white">Payment</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </template>
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
            status: "p",
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
                this.employers.unshift({ id: "", name: "All" })
            });
        },

        getSalary() {
            if (this.selectedEmployee == null) {
                alert("Select employee first")
                document.querySelector("#employee [type='search']").focus()
                return
            }

            let data = {
                employeeId: this.selectedEmployee.id,
                monthName: this.monthName,
                added_by: this.user_id,
                status: this.status
            }
            axios.post("/api/salary-generate", data)
                .then(res => {
                    this.salaries = res.data;
                })

        },

        salaryPayment(rowData) {
            if (rowData.date == null) {
                alert("Date field must be fill up")
                return
            }
            rowData.paymentStatus = "a";
            if (confirm("Are you sure")) {
                axios.post("/api/salary-payment", rowData)
                    .then(res => {
                        if(res.data.error){
                            this.$toastr.e(res.data.error, "Wrong!");
                            return
                        }else{
                            this.$toastr.s(res.data.msg, "Success!");
                            this.getSalary();
                        }
                    })
            }
        },

        salaryDue(rowData) {
            if (rowData.date == null) {
                alert("Date field must be fill up")
                return
            }
            rowData.paymentStatus = "d";
            if (confirm("Are you sure")) {
                axios.post("/api/salary-payment", rowData)
                    .then(res => {
                        if(res.data.error){
                            this.$toastr.e(res.data.error, "Wrong!");
                            return
                        }else{
                            this.$toastr.s(res.data.msg, "Success!");
                            this.getSalary();
                        }
                    })
            }
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
#employee {
    width: 100% !important;
}
</style>