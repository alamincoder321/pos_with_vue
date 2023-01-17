<template>
    <div class="container-fluid px-3 mt-2">
        <div class="row">
            <div class="col-lg-12 col-12">
                <div class="card">
                    <div class="card-header" style="background:linear-gradient(45deg, #bb3a87, #000000d1);">
                        <form @submit.prevent="getSearchSale">
                            <div class="row">
                                <div class="col-lg-2">
                                    <div class="form-group m-0">
                                        <select class="form-select shadow-none" v-model="searchBy">
                                            <option value="">All</option>
                                            <option value="invoice"> By Invoice </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-3" :style="{ display: searchBy == 'invoice' ? '' : 'none' }">
                                    <div class="form-group m-0">
                                        <v-select :options="invoices" id="invoice" v-model="selectedInvoice"
                                            label="invoice"></v-select>
                                    </div>
                                </div>
                                <div class="col-lg-2" :style="{ display: searchBy == 'invoice' ? 'none' : '' }">
                                    <div class="form-group m-0">
                                        <input type="date" class="form-control shadow-none" v-model="dateFrom" />
                                    </div>
                                </div>
                                <div class="col-lg-2" :style="{ display: searchBy == 'invoice' ? 'none' : '' }">
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
                    </div>
                    <div class="card-body" style="overflow-x:auto;"
                        :style="{ display: sales.length > 0 ? '' : 'none' }">
                        <table class="table table-bordered m-0">
                            <thead class="bg-info text-white text-center">
                                <tr>
                                    <th style="width: 8%">
                                        Sl
                                    </th>
                                    <th style="width: 10%">
                                        #Invoice
                                    </th>
                                    <th style="width: 10%">
                                        Date
                                    </th>
                                    <th>
                                        Customer Details
                                    </th>
                                    <th>
                                        Amount Details
                                    </th>
                                    <th style="width: 12%">
                                        Action
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <template v-for="(item, index) in sales">
                                    <tr>
                                        <td class="text-center">
                                            {{ index + 1 }}
                                        </td>
                                        <td class="text-center">
                                            {{ item.invoice }}
                                        </td>
                                        <td class="text-center">
                                            {{ formatDate(item.date) }}
                                        </td>
                                        <td>
                                            <span>Customer Name:
                                                {{ item.name }}</span><br />
                                            <span>Mobile: {{ item.phone }}</span><br />
                                            <span>Address:
                                                {{ item.address }}</span><br />
                                            <span v-if="item.customer_type != 'G'">Previous Due:
                                                {{ item.previous_due }}</span>
                                            <span v-else>General Customer</span>
                                        </td>
                                        <td>
                                            <span>SubTotal:
                                                {{ item.subtotal }}</span><br />
                                            <span>Total: {{ item.total }}</span><br />
                                            <span>Due: {{ item.due }}</span><br />
                                            <span v-if="item.discount != 0">Discount ({{
                                                item.discount
                                            }}%):
                                                {{ item.discount_amount }}</span><br />
                                            <span v-if="item.vat != 0">Vat ({{ item.vat }}%):
                                                {{ item.vat_amount }}</span><br />
                                            <span v-if="item.transport_cost != 0">Transport Cost:
                                                {{ item.transport_cost }}</span>
                                        </td>
                                        <td>
                                            <div class="input-group gap-2">
                                                <router-link class="bg-common" style="padding:2px 6px;"
                                                    title="Sale Invoice"
                                                    :to="{ path: '/invoice/' + item.invoice }"><i
                                                        class="fas fa-file text-info"></i>
                                                </router-link>
                                                <router-link class="bg-common" title="Sale-edit"
                                                    :to="{ path: '/sales-edit/' + item.invoice }">
                                                    <i class="fa fa-edit text-primary"></i>
                                                </router-link>
                                                <button title="Sale Delete" @click="InvoiceDelete(item.id, index)"
                                                    type="button" class="shadow-none outline-none border-0">
                                                    <i class="fas fa-trash text-danger"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                    <div class="card-body" :style="{ display: sales.length > 0 ? 'none' : '' }">
                        <p class="m-0 text-center">Not Found Data in Table</p>
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
            invoices: [],
            selectedInvoice: null,
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
            if (this.searchBy == "invoice" && this.selectedInvoice == null) {
                alert("Select first Invoice");
                document.querySelector("#invoice [type='search']").focus();
                return;
            }

            let data = {
                invoice: this.searchBy != "" ? this.selectedInvoice.invoice : "",
                dateFrom: this.searchBy != "" ? "" : this.dateFrom,
                dateTo: this.searchBy != "" ? "" : this.dateTo,
            };
            axios.post("/api/get_sale", data).then((res) => {
                this.sales = res.data.sales
            });
        },
        getSale() {
            axios.post("/api/get_sale").then((res) => {
                this.invoices = res.data.sales
            });
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
        searchBy() {
            if (this.searchBy == "") {
                this.selectedInvoice = null;
            }
        },
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
</style>