<template>
    <div class="container-fluid px-3 mt-2">
        <div class="row">
            <div class="col-lg-12 col-12">
                <div class="card">
                    <div class="card-header" style="background:linear-gradient(45deg, #bb3a87, #000000d1);">
                        <form @submit.prevent="getSearchQuotation">
                            <div class="row">
                                <div class="col-lg-2">
                                    <div class="form-group m-0">
                                        <select class="form-select shadow-none" v-model="searchBy">
                                            <option value="">All</option>
                                            <option value="invoice"> By Invoice </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-3" v-if="searchBy == 'invoice'">
                                    <div class="form-group m-0">
                                        <v-select :options="invoices" id="invoice" v-model="selectedInvoice"
                                            label="invoice"></v-select>
                                    </div>
                                </div>
                                <div class="col-lg-2" v-if="searchBy != 'invoice'">
                                    <div class="form-group m-0">
                                        <input type="date" class="form-control shadow-none" v-model="dateFrom" />
                                    </div>
                                </div>
                                <div class="col-lg-2" v-if="searchBy != 'invoice'">
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
                    <div class="card-body" style="overflow-x:auto;" v-if="quotations.length > 0">
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
                                <template v-for="(item, index) in quotations">
                                    <tr :title="item.user_name">
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
                                                {{ item.customer_name }}</span><br />
                                            <span>Mobile: {{ item.customer_phone }}</span><br />
                                            <span>Address:
                                                {{ item.customer_address }}</span>
                                        </td>
                                        <td>
                                            <span>SubTotal:
                                                {{ item.subtotal }}</span><br />
                                            <span>Total: {{ item.total }}</span><br />
                                            <span v-if="item.discount != 0">Discount ({{
                                                item.discount
                                            }}%):
                                                {{ item.discount_amount }}</span><br />
                                            <span v-if="item.vat != 0">Vat ({{ item.vat }}%):
                                                {{ item.vat_amount }}</span><br />
                                            <span v-if="item.transport_cost != 0">Transport Cost:
                                                {{ item.transport_cost }}</span>
                                        </td>
                                        <td style="width: 13%;">
                                            <div class="input-group gap-2">
                                                <button @click="showDetail(index)" title="Show Quotation Details"
                                                    class="shadow-none outline-none border-0" :class="'ShowBtn-' + index"
                                                    type="button"><i
                                                        class="bi bi-info-circle-fill text-primary"></i></button>
                                                <button @click="hideDetail(index)" title="Close Quotation Details"
                                                    class="shadow-none d-none outline-none border-0"
                                                    :class="'CloseBtn-' + index" type="button">
                                                    <i class="bi bi-x-square-fill text-danger"></i>
                                                </button>
                                                <router-link class="bg-common" style="padding:2px 6px;"
                                                    title="Quotation Invoice"
                                                    :to="{ path: '/quotation-invoice/' + item.invoice }"><i
                                                        class="fas fa-file text-info"></i>
                                                </router-link>
                                                <router-link class="bg-common" title="Quotation Edit"
                                                    :to="{ path: '/quotations-edit/' + item.invoice }">
                                                    <i class="fa fa-edit text-primary"></i>
                                                </router-link>
                                                <button title="Quotation Delete" @click="InvoiceDelete(item.id, index)"
                                                    type="button" class="shadow-none outline-none border-0">
                                                    <i class="bi bi-trash text-danger"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr :title="'Invoice: '+item.invoice" class="d-none" :class="'showDetails-' + index">
                                        <td colspan="6">
                                            <table class="table table-sm table-hover table-bordered">
                                                <thead class="text-white text-center"
                                                    style="background:linear-gradient(180deg, rgb(255 14 14), rgb(0 243 255))">
                                                    <tr>
                                                        <th>Sl</th>
                                                        <th>Description</th>
                                                        <th>Quantity</th>
                                                        <th>Unit Price</th>
                                                        <th>Total</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr v-for="(detail, index) in item.quotationDetails">
                                                        <td>{{ index + 1 }}</td>
                                                        <td>{{ detail.name }}</td>
                                                        <td class="text-center">{{ detail.quantity }} {{ detail.unit_name }}</td>
                                                        <td class="text-center">{{ detail.selling_price }}</td>
                                                        <td class="text-end">{{ detail.total_amount }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th colspan="4" class="text-center">Total:</th>
                                                        <th class="text-end">{{
                                                            item.quotationDetails.reduce((acc, pre) => {
                                                                return acc +
                                                                    +pre.total_amount
                                                            }, 0).toFixed(2)
                                                        }}</th>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
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
var moment = require('moment');
export default {
    data() {
        return {
            searchBy: "",
            dateFrom: moment().format("YYYY-MM-DD"),
            dateTo: moment().format("YYYY-MM-DD"),
            invoices: [],
            selectedInvoice: null,
            quotations: [],
            useraccess: [],
            user_id: null,
        }
    },
    created() {
        this.user_id = localStorage.getItem("user_id");
        this.getSearchQuotation();
        this.getQuotation();
        this.getPermission();
        this.logOut();
    },
    methods: {
        showDetail(index) {
            $(".ShowBtn-" + index).addClass("d-none")
            $(".CloseBtn-" + index).removeClass("d-none")
            $(".showDetails-" + index).removeClass("d-none");
        },
        hideDetail(index) {
            $(".ShowBtn-" + index).removeClass("d-none")
            $(".CloseBtn-" + index).addClass("d-none")
            $(".showDetails-" + index).addClass("d-none");
        },
        getSearchQuotation() {
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
            axios.post("/api/get-quotation", data).then((res) => {
                this.quotations = res.data.quotations
            });
        },
        getQuotation() {
            axios.post("/api/get-quotation").then((res) => {
                this.invoices = res.data.quotations
            });
        },

        InvoiceDelete(id, sl) {
            if (confirm("Are you sure want to delete")) {
                axios.get("/api/delete-quotation/" + id).then((res) => {
                    this.$toastr.s(res.data, "Success!");
                    var index = this.quotations.indexOf(sl);
                    this.quotations.splice(index, 1);
                });
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
        searchBy() {
            if (this.searchBy == "") {
                this.selectedInvoice = null;
            }
        },
        useraccess() {
            this.useraccess.includes("quotation.index") ? "" : location.href = "/unauthorize"
        }
    },

    mounted() {
        document.title = "Quotation List Page"
    },
};
</script>

<style>
#invoice {
    width: 100% !important;
}
</style>