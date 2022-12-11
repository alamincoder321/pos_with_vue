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
                                    <select class="form-control shadow-none" v-model="changeVal"
                                        @change="onChangeValue">
                                        <option value="">By Current Date</option>
                                        <option value="invoice">By Invoice</option>
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
                                    <label for="dateFrom">From Date:</label>
                                    <VueDatePicker v-model="dateFrom" :style="color" format="DD-MM-YYYY" />
                                </div>
                            </div>
                            <div class="col-lg-2" :style="{ display: changeVal == '' ? '' : 'none' }">
                                <div class='form-group'>
                                    <label>To Date:</label>
                                    <VueDatePicker v-model="dateTo" :style="color" format="DD-MM-YYYY" />
                                </div>
                            </div>
                            <div class="col-lg-1 mt-lg-0 mt-3">
                                <label></label>
                                <button type="button" @click="getSearchSale" class="searchBtn">Submit</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-12 col-12">
                <table id="getTable" class="table table-sm table-bordered border-primary">
                    <thead style="background: #897800;color: white;">
                        <tr class="text-center" style="font-size: 12px;">
                            <th>Invoice No.</th>
                            <th>Date</th>
                            <th>Customer Details</th>
                            <th>Sale Amount Details</th>
                            <th class="hideAction">Action</th>
                        </tr>
                    </thead>
                    <tbody style="border:0; font-size: 12px;">
                        <tr v-for="(item, index) in sales" :key="index">
                            <td>#{{ item.invoice }}</td>
                            <td>{{ formatDate(item.date) }}</td>
                            <td>
                                <span style="font-weight: bold;">Name:</span> {{ item.name }}<br />
                                <span style="font-weight: bold;">Phone:</span> {{ item.phone }} <br />
                                <span style="font-weight: bold;">Address:</span> {{ item.address }} <br />
                                <span style="font-weight: bold;">Previous Due:</span> {{ item.previous_due }}
                            </td>
                            <td>
                                <span style="font-weight: bold;">SubTotal:</span> {{ item.subtotal }} <br />
                                <span style="font-weight: bold;">Total:</span> {{ item.total }} <br />
                                <span style="font-weight: bold;">Paid:</span> {{ item.paid }} <br />
                                <span style="font-weight: bold;">Due:</span> {{ item.due }} <br />
                                <span style="font-weight: bold;">Vat ({{ item.vat }})%:</span> {{ item.vat_amount }}
                                <br />
                                <span style="font-weight: bold;">Discount ({{ item.discount }})%:</span> {{
                                        item.discount_amount
                                }}
                            </td>
                            <td class="hideAction">
                                <span title="Invoice Delete" @click="InvoiceDelete(item.id, index)"
                                    style="cursor:pointer; margin-right: 5px;"><i
                                        class="fas fa-trash text-danger"></i></span>
                                <router-link title="Sales Edit" style="margin-right: 5px;"
                                    :to="{ path: '/sales-edit/' + item.invoice }">
                                    <i class="fa fa-edit text-primary"></i>
                                </router-link>
                                <router-link title="invoice" :to="{ path: '/invoice/' + item.invoice }"
                                    style="cursor:pointer;"><i class="fas fa-file text-info"></i></router-link>
                            </td>
                        </tr>
                        <tr :style="{ display: sales.length == 0 ? '' : 'none' }">
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

            sales: [],
            useraccess: [],
            user_id: null,
        }
    },
    created() {
        this.user_id = localStorage.getItem("user_id");
        this.getSearchSale();
        this.getSale();
        this.getPermission();
        this.logOut();
    },
    methods: {
        getSearchSale() {
            let data = {
                dateFrom: this.selectedInvoice.invoice ? "" : this.dateFrom,
                dateTo: this.dateTo,
                invoice: this.selectedInvoice != null || this.selectedInvoice.invoice != "" ? this.selectedInvoice.invoice : ""
            }
            axios.post("/api/get_sale", data).then((res) => {
                this.sales = res.data.sales
            });
        },
        getSale() {
            axios.post("/api/get_sale").then((res) => {
                this.invoices = res.data.sales
            });
        },
        onChangeValue() {
            if (this.changeVal == '') {
                this.selectedInvoice = {
                    id: "",
                    invoice: ""
                }
            }
        },

        InvoiceDelete(id, sl) {
            if (confirm("Are you sure want to delete")) {
                axios.get("/api/delete_sale/" + id).then((res) => {
                    alert(res.data);
                    var index = this.sales.indexOf(sl);
                    this.sales.splice(index, 1);
                });
            }
        },

        async PrintInvoice() {
            var myWindow = window.open('', 'PRINT');
            myWindow.document.write(`


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
            this.useraccess.includes("sale.index") ? "" : location.href = "/unauthorize"
        }
    },

    mounted() {
        document.title = "Sale List Page"
    },
};
</script>

<style>
#invoice {
    width: 100% !important;
}

.searchBtn {
    border: none;
    background: green;
    color: white;
    padding: 3px 12px;
    font-size: 15px;
    border-radius: 0.2rem;
}
</style>