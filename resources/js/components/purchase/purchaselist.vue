<template>
    <div class="container-fluid px-3 mt-2">
        <div class="row">
            <div class="col-xl-12 col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label for="changeVal"></label>
                                    <select class="form-control shadow-none" v-model="changeVal">
                                        <option value="">By Current Date</option>
                                        <option value="invoice">Invoice</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-4" :style="{ display: changeVal == 'invoice' ? '' : 'none' }">
                                <div class="form-group">
                                    <label for="invoice">Invoice</label>
                                    <v-select label="invoice" id="invoice" :options="invoices"
                                        v-model="selectedInvoice">
                                    </v-select>
                                </div>
                            </div>
                            <div class="col-lg-2" :style="{ display: changeVal == '' ? '' : 'none' }">
                                <div class="form-group">
                                    <label for="dateFrom">DateFrom:</label>
                                    <VueDatePicker v-model="dateFrom" :style="color" format="DD-MM-YYYY" />
                                </div>
                            </div>
                            <div class="col-lg-2" :style="{ display: changeVal == '' ? '' : 'none' }">
                                <div class='form-group'>
                                    <label>DateTo:</label>
                                    <VueDatePicker v-model="dateTo" :style="color" format="DD-MM-YYYY" />
                                </div>
                            </div>
                            <div class="col-lg-1 mt-lg-0 mt-3">
                                <label></label>
                                <button type="button" @click="getSearchPurchase" class="searchBtn">Submit</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-12 col-12">
                <table class="table table-sm table-bordered border-primary">
                    <thead style="background: #897800;color: white;">
                        <tr class="text-center" style="font-size: 12px;">
                            <th>Invoice No.</th>
                            <th>Date</th>
                            <th>Supplier Details</th>
                            <th>Purchase Details</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody style="border:0; font-size: 12px;">
                        <tr v-for="(item, index) in purchases" :key="index">
                            <td>#{{ item.invoice }}</td>
                            <td>{{ formatDate(item.date) }}</td>
                            <td>
                                <span style="font-weight: bold;">Name:</span> {{ item.name }}<br />
                                <span style="font-weight: bold;">Phone:</span> {{ item.phone }} <br />
                                <span style="font-weight: bold;">Address:</span> {{ item.address }}
                            </td>
                            <td>
                                <span style="font-weight: bold;">SubTotal:</span> {{ item.subtotal }} <br />
                                <span style="font-weight: bold;">Total:</span> {{ item.total }} <br />
                                <span style="font-weight: bold;">Paid:</span> {{ item.paid }} <br />
                                <span style="font-weight: bold;">Due:</span> {{ item.due }}
                            </td>
                            <td>
                                <router-link :to="{ path: '/purchases' }">
                                    <i class="fa fa-edit text-primary hidden-print"></i>
                                </router-link>
                                <span @click="PrintInvoice" style="cursor:pointer;"><i
                                        class="fas fa-print text-info hidden-print"></i></span>
                            </td>
                        </tr>
                        <tr :style="{ display: purchases.length == 0 ? '' : 'none' }">
                            <td colspan="5" class="text-center">Not Found Data</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
var moment = require('moment');
export default {
    data() {
        return {
            color: {
                background: '#ffadb4',
                borderRadius: '0.2rem',
                height: '28px',
            },
            changeVal: "",
            dateFrom: moment(new Date()).format("YYYY-MM-DD"),
            dateTo: moment(new Date()).format("YYYY-MM-DD"),
            invoices: [],
            selectedInvoice: {
                id: "",
                invoice: "",
            },

            purchases: [],
            useraccess: [],
            user_id: null,
        }
    },
    created() {
        this.user_id = localStorage.getItem("user_id");
        this.getSearchPurchase();
        this.getPurchases();
        this.getPermission();
        this.logOut();
    },
    methods: {
        getSearchPurchase() {
            let data = {
                dateFrom: this.selectedInvoice.invoice ? "" : this.dateFrom,
                dateTo: this.dateTo,
                invoice: this.selectedInvoice != null || this.selectedInvoice.invoice != "" ? this.selectedInvoice.invoice : ""
            }
            axios.post("/api/get_purchase", data).then((res) => {
                this.purchases = res.data.purchases
            });
        },
        getPurchases() {
            axios.post("/api/get_purchase").then((res) => {
                this.invoices = res.data.purchases
            });
        },

        async PrintInvoice() {
            var myWindow = window.open('', '');
            myWindow.document.write(`
				<html>
					<head>
						<title>Print</title>	
					</head>
					<body>                        
						${document.querySelector("table").innerHTML}
					</body>
				</html>

			`);
            myWindow.focus();
            await new Promise(resolve => setTimeout(resolve, 1000));
            myWindow.print();
            myWindow.close();
        },

        formatDate(date) {
            return moment(date).format("DD-MM-YYYY");
        },

        getPermission() {
            axios.get("/api/get_permission/" + this.user_id).then((res) => {
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
            this.useraccess.includes("purchase.index") ? "" : location.href = "/unauthorize"
        }
    },

    mounted() {
        document.title = "Purchase List Page"
    },
};
</script>

<style>
.searchBtn {
    border: none;
    background: green;
    color: white;
    padding: 3px 12px;
    font-size: 15px;
    border-radius: 0.2rem;
}
</style>