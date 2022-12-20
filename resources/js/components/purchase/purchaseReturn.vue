<template>
    <div class="container-fluid px-3 mt-2">
        <div class="row">
            <div class="col-xl-12 col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="supplier">Supplier</label>
                                    <v-select style="width:100% !important" :options="suppliers" label="display_name" id="supplier"
                                        v-model="selectedSupplier" @input="onChangeSupplier">
                                    </v-select>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label for="invoice">Invoice</label>
                                    <v-select label="invoice" id="invoice" :options="invoices"
                                        v-model="selectedInvoice">
                                    </v-select>
                                </div>
                            </div>
                            <div class="col-lg-1 mt-lg-0 mt-3">
                                <label></label>
                                <button type="button" @click="getPurchases" class="shadow-none btn btn-success btn-sm px-3">Submit</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-12 col-12" style="overflow-x:auto;">
                <table id="getTable" class="table table-sm table-bordered border-primary">
                    <thead style="background: #897800;color: white;">
                        <tr>
                            <th>Sl</th>
                            <th>Product Name</th>
                            <th>Product Price</th>
                            <th>Quantity</th>
                            <th>Total Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item, index) in purchases.purchaseDetails" :key="index">
                            <td>{{ index+1 }}</td>
                            <td>{{ item.name }}</td>
                            <td>{{ item.purchase_price }}</td>
                            <td>{{ item.quantity }}</td>
                            <td>{{ item.total_amount }}</td>
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
            changeVal: "",
            dateFrom: moment(new Date()).format("YYYY-MM-DD"),
            dateTo: moment(new Date()).format("YYYY-MM-DD"),
            suppliers: [],
            selectedSupplier: null,
            invoices: [],
            selectedInvoice: null,
            purchases: {},
            useraccess: [],
            user_id: null,
        }
    },
    created() {
        this.user_id = localStorage.getItem("user_id");
        this.getSupplier();
        this.getPermission();
        this.logOut();
    },
    methods: {
        getSupplier() {
            axios.get("/api/get_supplier").then((res) => {
                this.suppliers = res.data.suppliers
            });
        },
        getPurchases() {
            if(this.selectedSupplier == null){
                alert("Select supplier")
                document.querySelector("#supplier [type='search']").focus()
                return
            }
            if(this.selectedInvoice == null){
                alert("Select invoice")
                document.querySelector("#invoice [type='search']").focus()
                return
            }
            let data = {
                supplier_id: this.selectedSupplier.id,
                invoice: this.selectedInvoice.invoice
            }
            axios.post("/api/get_purchase", data).then((res) => {
                this.purchases = res.data.purchases[0]
            });
        },
        getInvoice(id) {
            axios.post("/api/get_purchase", {supplier_id: id}).then((res) => {
                this.invoices = res.data.purchases
            });
        },

        onChangeSupplier(){
            this.invoices = []
            this.selectedInvoice = null
            if(this.selectedSupplier == null){
                return
            }
            this.getInvoice(this.selectedSupplier.id);
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
        document.title = "Purchase Return Page"
    },
};
</script>