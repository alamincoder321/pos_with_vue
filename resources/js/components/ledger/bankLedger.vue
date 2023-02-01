<template>
    <div class="container-fluid px-3 mt-2">
        <div class="row">
            <div class="col-12 col-lg-12">
                <div class="card">
                    <div class="card-header position-relative"
                        style="background:linear-gradient(45deg, #bb3a87, #000000d1);">
                        <form @submit.prevent="getLedger">
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group m-0">
                                        <v-select :options="accounts" id="accounts" v-model="selectedAccount"
                                            label="display_name"></v-select>
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="form-group m-0">
                                        <input type="date" class="form-control shadow-none" v-model="dateFrom" />
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="form-group m-0">
                                        <input type="date" class="form-control shadow-none" v-model="dateTo" />
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="form-group m-0">
                                        <button type="submit" class="btn btn-info btn-sm text-white shadow-none px-3">
                                            Submit
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <button class="btn btn-sm shadow-none text-white btn-print" @click="print"
                            v-if="ledgers.length > 0"><i class="bi bi-printer"></i> Print</button>
                    </div>
                    <div class="card-body" style="overflow-x:auto;" v-if="ledgers.length > 0">
                        <table class="table table-sm table-hover table-bordered" id="ledgers">
                            <thead style="background: #897800;">
                                <tr class="text-center text-white" style="font-size: 12px;">
                                    <th>Sl</th>
                                    <th>Description</th>
                                    <th>Date</th>
                                    <th>Transaction Type</th>
                                    <th>Deposit</th>
                                    <th>Withdraw</th>
                                    <th>Current Balance</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th colspan="6" style="padding-left:44px;">Previous Balance</th>
                                    <td>{{ previousBalance.toFixed(2) }}</td>
                                </tr>
                                <template v-for="(item, index) in ledgers">
                                    <tr>
                                        <td>{{ index + 1 }}</td>
                                        <td>{{ item.description }}</td>
                                        <td>{{ formatDate(item.date) }}</td>
                                        <td class="text-capitalize text-center">{{ item.transaction_type }}</td>
                                        <td>{{ item.deposit }}</td>
                                        <td>{{ item.withdraw }}</td>
                                        <td>{{ (item.balance).toFixed(2) }}</td>
                                    </tr>
                                </template>
                            </tbody>
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
var moment = require("moment");
export default {
    data() {
        return {
            dateFrom: moment().format("YYYY-MM-DD"),
            dateTo: moment().format("YYYY-MM-DD"),
            accounts: [],
            selectedAccount: null,
            ledgers: [],
            previousBalance: 0,
            company: [],
            useraccess: [],
            user_id: null,
        };
    },
    created() {
        this.user_id = localStorage.getItem("user_id");
        this.getCompany();
        this.getAccount();
        this.getPermission();
        this.logOut();
    },
    methods: {
        getCompany() {
            axios.get("/api/get-company-profile").then((res) => {
                this.company = res.data;
            });
        },
        getAccount() {
            axios.get("/api/get-bankaccount").then((res) => {
                this.accounts = res.data;
            });
        },
        getLedger() {
            if (this.selectedAccount == null) {
                alert("Select first Bank Name");
                document.querySelector("#accounts [type='search']").focus();
                return;
            }

            let data = {
                id: this.selectedAccount != null ? this.selectedAccount.id : "",
                dateFrom: this.dateFrom,
                dateTo: this.dateTo,
            };

            axios.post("/api/getbank-ledger", data).then((res) => {
                this.ledgers = res.data.ledger.filter(ledger => {
                    return ledger.date >= this.dateFrom && ledger.date <= this.dateTo;
                });
                this.previousBalance = res.data.previousBalance;
            });
        },

        formatDate(date) {
            return moment(date).format("DD-MM-YYYY");
        },

        async print() {
            var myWindow = window.open('', '', `width=${window.screen.width},height=${window.screen.height}`);
            myWindow.document.write(`
                    <html>
                        <head>
                            <title>Bank Ledger</title>
                            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" />
                            <style>
                                * {
                                    font-family: unset;
                                }   
                                h2 {
                                    color: #939393 !important;
                                }    
                                @media print {
                                    *{
                                        margin:0 auto;
                                    }
                                    .table thead {
                                        background: #897800 !important;
                                        color: white !important;
                                    }
                                    #ledgers{
                                        page-break-inside:avoid;
                                        page-break-after:always;
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
                                    <h2 style="margin:0;text-align:center">Bank Ledger of ${this.selectedAccount.bank_name}</h2>
                                    <table class="table table-sm table-bordered m-0">
                                        ${document.querySelector("#ledgers").innerHTML}
                                    </table>
                                </div>
                                <div style='width:100%;position:fixed;bottom:0;left:0;text-align:center;padding-top:8px;font-style:italic;'>Print Date: ${moment().format("DD-MM-YYYY")}<div>
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
        selectedAccount() {
            this.ledgers = []
        },
        useraccess() {
            this.useraccess.includes("purchase.index")
                ? ""
                : (location.href = "/unauthorize");
        },
    },

    mounted() {
        document.title = "Bank Ledger Page";
    },
};
</script>

<style>
#accounts {
    width: 100% !important;
}
</style>
