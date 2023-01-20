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
                                    <v-select style="width:100% !important" :options="suppliers" label="display_name"
                                        id="supplier" v-model="selectedSupplier" @input="onChangeSupplier">
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
                                <button type="button" @click="getPurchases"
                                    class="shadow-none btn btn-success btn-sm px-3">Submit</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-12 col-12" style="overflow-x:auto;" v-if="purchases.length > 0">
                <div class="row my-3">
                    <div class="col-12 col-lg-6">
                        <div class="form-group" style="width:210px;">
                            <label for="return_date">Return Date</label>
                            <input type="date" class="form-control shadow-none" v-model="date">
                        </div>
                    </div>
                    <div class="col-12 col-lg-6 text-end">
                        <h5>Supplier Information</h5>
                        Name: {{ selectedSupplier.name }} <br />
                        Phone: {{ selectedSupplier.phone }} <br />
                        Address: {{ selectedSupplier.address }}
                    </div>
                </div>
                <table id="getTable" class="table table-sm table-bordered border-primary">
                    <thead style="background: #897800;color: white;">
                        <tr>
                            <th>Sl</th>
                            <th>Product Name</th>
                            <th>Product Price</th>
                            <th>Quantity</th>
                            <th>Already return Qty</th>
                            <th>Already return Price</th>
                            <th>Return Qty</th>
                            <th>Return Price</th>
                            <th>Return Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item, index) in purchases[0].purchaseDetails" :key="index">
                            <td>{{ index + 1 }}</td>
                            <td>{{ item.name }}</td>
                            <td>{{ item.total_amount }}</td>
                            <td>{{ item.quantity }}</td>
                            <td>{{ item.return_quantity }}</td>
                            <td>{{ item.return_price }}</td>
                            <td>
                                <input type="number" min="0" v-model="item.return_quantity"
                                    class="form-control shadow-none">
                            </td>
                            <td>
                                <input type="number" min="0" v-model="item.return_price"
                                    class="form-control shadow-none">
                            </td>
                            <td>0.00</td>
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
            date: moment(new Date()).format("YYYY-MM-DD"),
            suppliers: [],
            selectedSupplier: null,
            invoices: [],
            selectedInvoice: null,
            purchases: [],
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
            axios.get("/api/get-supplier").then((res) => {
                this.suppliers = res.data.suppliers.filter(s => s.supplier_type != "G")
                this.suppliers.unshift({id: "0", display_name: "General Supplier"})
            });
        },
        getPurchases() {
            if (this.selectedSupplier == null) {
                alert("Select supplier")
                document.querySelector("#supplier [type='search']").focus()
                return
            }
            if (this.selectedInvoice == null) {
                alert("Select invoice")
                document.querySelector("#invoice [type='search']").focus()
                return
            }
            let data = {
                supplier_id: this.selectedSupplier.id,
                invoice: this.selectedInvoice.invoice
            }
            axios.post("/api/get-purchase", data).then((res) => {
                this.purchases = res.data.purchases
            });
        },
        getInvoice(id) {
            axios.post("/api/get-purchase", { supplier_id: id }).then((res) => {
                if (this.selectedSupplier.id == "0") {
                    this.invoices = res.data.purchases.filter(inv => inv.supplier_type == "G");
                } else {
                    this.invoices = res.data.purchases;
                }
            });
        },

        onChangeSupplier() {
            this.invoices = []
            this.selectedInvoice = null
            if (this.selectedSupplier == null) {
                return
            }
            if(this.selectedSupplier.id == "0"){
                this.getInvoice();
                this.invoices
            }else{
                this.getInvoice(this.selectedSupplier.id);
            }
        },

        formatDate(date) {
            return moment(date).format("DD-MM-YYYY");
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
            this.useraccess.includes("purchase.index") ? "" : location.href = "/unauthorize"
        },
    },

    mounted() {
        document.title = "Purchase Return Page"
    },
};
</script>