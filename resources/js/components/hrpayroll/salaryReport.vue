<template>
    <div class="container-fluid mt-2">
        <div class="row">
            <div class="col-12 col-lg-12">
                <div class="card">
                    <div class="card-header position-relative"
                        style="background:linear-gradient(45deg, #bb3a87, #000000d1);">
                        <div class="row">
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <select class="form-select" v-model="searchBy">
                                        <option value="">All</option>
                                        <option value="employee">By Employee</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3" v-if="searchBy == 'employee'">
                                <div class="form-group">
                                    <v-select :options="employers" id="employee" v-model="selectedEmployee"
                                        label="name"></v-select>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <input type="date" v-model="dateFrom" class="form-control" />
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <input type="date" v-model="dateTo" class="form-control" />
                                </div>
                            </div>

                            <div class="col-lg-1">
                                <button type="button" @click="getSalary"
                                    class="btn btn-info btn-sm text-white shadow-none px-3">
                                    Submit
                                </button>
                            </div>
                        </div>
                        <button v-if="salaries.length > 0" class="btn btn-sm shadow-none text-white btn-print"
                            @click="print"><i class="bi bi-printer"></i> Print</button>
                    </div>
                    <div class="card-body" v-if="salaries.length > 0">
                        <table id="paymentReport" class="table table-sm table-hover table-bordered">
                            <template v-for="(item, index) in salaries">                                    
                            <thead style="background: #897800;color: white;">
                                <tr class="text-center text-white" style="background:darkslategrey;">
                                    <th colspan="8">{{ item.name }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                    <tr>
                                        <th>Sl</th>
                                        <th>Payment Date</th>
                                        <th>Description</th>
                                        <th>Advance</th>
                                        <th>OverTimeBonus</th>
                                        <th>LeaveDeduction</th>
                                        <th>Salary</th>
                                        <th class="lastTh">Action</th>
                                    </tr>
                                    <tr v-for="(val, index) in item.salary">
                                        <td>{{ ++index }}</td>
                                        <td>{{ formatDate(val.date) }}</td>
                                        <td>{{ val.description }}</td>
                                        <td>{{ val.advance }}</td>
                                        <td>{{ val.overTimeBonus }}</td>
                                        <td>{{ val.leaveDeduction }}</td>
                                        <td>{{ val.salary }}</td>
                                        <td class="lastTd">Action</td>
                                    </tr>
                                </tbody>
                            </template>
                        </table>
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
            searchBy: "",
            dateFrom: moment().format("YYYY-MM-DD"),
            dateTo: moment().format("YYYY-MM-DD"),
            employers: [],
            selectedEmployee: null,
            salaries: [],
            company: [],
            useraccess: [],
            user_id: null,
        }
    },
    created() {
        this.user_id = localStorage.getItem("user_id");
        this.getPermission();
        this.getEmployee();
        this.getCompany();
        this.logOut();
    },
    methods: {
        getCompany() {
            axios.get("/api/get-company-profile").then((res) => {
                this.company = res.data;
            });
        },
        getEmployee() {
            axios.get("/api/get-employer").then((res) => {
                this.employers = res.data.employers;
            });
        },

        getSalary() {
            if (this.searchBy == 'employee') {
                if (this.selectedEmployee.id == "" && this.selectedEmployee == null) {
                    alert("Select employee first")
                    document.querySelector("#employee [type='search']").focus()
                    return
                }
            }

            let data = {
                employeeId: this.searchBy == "employee" ? this.selectedEmployee.id : "",
                dateFrom: this.dateFrom,
                dateTo: this.dateTo,
            }
            axios.post("/api/salary-report", data)
                .then(res => {
                    this.salaries = res.data;
                })

        },

        formatDate(date) {
            return moment(date).format("DD-MM-YYYY");
        },

        async print() {
            var myWindow = window.open('', '', `width=${window.screen.width},height=${window.screen.height}`);
            myWindow.document.write(`
                    <html>
                        <head>
                            <title>Salary Payment Report</title>
                            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" />
                            <style>
                                * {
                                    font-family: unset;
                                }    
                                img {
                                    width: 100%;
                                    height: 70px;
                                }    
                                h2 {
                                    color: #939393 !important;
                                }
                                .lastTh, .lastTd{
                                    display: none;
                                }    
                                @media print {
                                    *{
                                        margin:0 auto;
                                    }
                                    .table thead {
                                        background: #897800 !important;
                                        color: white !important;
                                    }
                                    #paymentReport{
                                        page-break-inside:avoid;
                                        page-break-after:always;
                                    }
                                    .lastTh, .lastTd{
                                        display: none;
                                    }
                                }
                            </style>
                        </head>
                        <body>
                            <div class='container'>
                                <div class="row">
                                    <div class="col-12 text-center" style="border-bottom:1.5px dashed gray;">
                                        <h2 class="text-uppercase m-0">${this.company.name}</h2>
                                        <p class="m-0 text-uppercase">${this.company.owner_name}</p>
                                        <p class="m-0 text-uppercase">${this.company.phone}</p>
                                        <address style="margin:0;">${this.company.address}</address>
                                    </div>
                                </div>
                                <div class="row">
                                    <h2 style="margin:0;text-align:center">Salary Payment Report</h2>
                                    <table class="table table-sm table-bordered m-0">
                                        <tr>
                                            <td>
                                                ${document.querySelector("#paymentReport").innerHTML}    
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <div style='width:100%;position:fixed;bottom:0;left:0;text-align:center;padding-top:8px;font-style:italic;'>Print Date: ${moment(new Date()).format("DD-MM-YYYY")}<div>
                            </div>
                        </body>
                    </html>
                `);
            myWindow.focus();
            await new Promise(resolve => setTimeout(resolve, 500));
            myWindow.print();
            myWindow.close();
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
        document.title = "Salary Payment Report"
    },
};
</script>
<style>
#employee {
    width: 100% !important;
}
</style>