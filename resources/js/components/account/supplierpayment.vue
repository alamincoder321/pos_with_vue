<template>
    <div class="container-fluid px-4">
        <div class="row">
            <div class="col-12 mt-3">
                <div class="card" :style="{ display: useraccess.includes('supplierpayment.store') ? '' : 'none' }">
                    <div class="card-header">
                        <h4 class="card-title">Supplier Payment Create</h4>
                    </div>
                    <form @submit.prevent="saveSupplierPayment">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 col-lg-5">
                                    <div class="row mt-2">
                                        <label for="payment_type"
                                            class="col-5 col-lg-4 d-flex align-items-center">Payment Type:</label>
                                        <div class="col-7 col-lg-8">
                                            <select name="payment_type" id="payment_type"
                                                v-model="supplierpayment.payment_type" class="form-control shadow-none">
                                                <option value="cash">Cash</option>
                                                <option value="bank">Bank</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mt-2"
                                        :style="{ display: supplierpayment.payment_type == 'bank' ? '' : 'none' }">
                                        <label for="bank_id" class="col-5 col-lg-4 d-flex align-items-center">Bank
                                            Name:</label>
                                        <div class="col-7 col-lg-8">
                                            <div class="input-group">
                                                <v-select label="display_name" id="bank" name="bank_id" :options="banks"
                                                    v-model="selectedBank" @input="onChangeBank"></v-select>
                                                <a href="/bankaccounts" target="_blank"
                                                    class="btn btn-success shadow-none" style="padding: 4px 8px;"><i
                                                        class="fas fa-plus"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <label for="supplier_id"
                                            class="col-5 col-lg-4 d-flex align-items-center">Supplier:</label>
                                        <div class="col-7 col-lg-8">
                                            <div class="input-group">
                                                <v-select label="display_name" id="supplier" name="supplier_id"
                                                    :options="suppliers" v-model="selectedSupplier"
                                                    @input="onChangeSupplier"></v-select>
                                                <a href="/suppliers" target="_blank" class="btn btn-success shadow-none"
                                                    style="padding: 4px 8px;"><i class="fas fa-plus"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <label for="due" class="col-5 col-lg-4 d-flex align-items-center">Due:</label>
                                        <div class="col-7 col-lg-8">
                                            <input type="number" id="due" name="due" class="form-control shadow-none"
                                                v-model="supplierpayment.due" autocomplete="off" readonly />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-lg-5">
                                    <div class="row mt-2">
                                        <label for="date" class="col-5 col-lg-4 d-flex align-items-center">Payment
                                            Date:</label>
                                        <div class="col-7 col-lg-8">
                                            <VueDatePicker v-model="supplierpayment.date" :style="color" />
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <label for="description"
                                            class="col-5 col-lg-4 d-flex align-items-center">Description:</label>
                                        <div class="col-7 col-lg-8">
                                            <textarea id="description" name="description"
                                                class="form-control shadow-none" v-model="supplierpayment.description"
                                                autocomplete="off"></textarea>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <label for="payment_amount"
                                            class="col-5 col-lg-4 d-flex align-items-center">Payment:</label>
                                        <div class="col-7 col-lg-8">
                                            <input type="number" min="0" id="payment_amount" name="payment_amount"
                                                class="form-control shadow-none"
                                                v-model="supplierpayment.payment_amount" autocomplete="off" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group text-center mt-3">
                                <button type="button" class="btn btn-outline-secondary btn-sm shadow-none"
                                    @click="clearData">
                                    Reset
                                </button>
                                <button :style="{ display: useraccess.includes('supplierpayment.store') ? '' : 'none' }"
                                    type="submit" class="btn btn-sm btn-outline-success shadow-none">
                                    Save SupplierPayment
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <hr class="my-2" />
            <div class="col-12 col-lg-12">
                <vue-good-table :columns="columns" :rows="supplierpayments" :fixed-header="true" :pagination-options="{
                    enabled: true,
                    perPage: 15,
                }" :search-options="{
    enabled: true,
}" :line-numbers="true" styleClass="vgt-table" max-height="550px">
                    <template slot="table-row" slot-scope="props">
                        <span v-if="props.column.field == 'before'">
                            <button :style="{ display: useraccess.includes('supplierpayment.edit') ? '' : 'none' }"
                                class="btn btn-sm btn-outline-primary shadow-none" @click="editRow(props.row)">
                                Edit
                            </button>
                            <button :style="{ display: useraccess.includes('supplierpayment.delete') ? '' : 'none' }"
                                class="btn btn-sm btn-outline-danger shadow-none" @click="deleteRow(props.row.id)">
                                Delete
                            </button>
                        </span>
                    </template>
                </vue-good-table>
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
            columns: [
                {
                    label: "Payment Date",
                    field: "date",
                },
                {
                    label: "Supplier",
                    field: "name",
                },
                {
                    label: "Payment Type",
                    field: "payment_type",
                },
                {
                    label: "Bank Name",
                    field: "bank_name",
                },
                {
                    label: "Payment Amount",
                    field: "payment_amount",
                },
                {
                    label: "Added By",
                    field: "user_name",
                },
                {
                    label: "Action",
                    field: "before",
                },
            ],
            supplierpayments: [],
            supplierpayment: {
                id: "",
                date: moment(new Date()).format("YYYY-MM-DD"),
                payment_type: "cash",
                bank_id: "",
                supplier_id: "",
                due: 0.00,
                payment_amount: 0.00,
                description: "",
                added_by: "",
            },
            suppliers: [],
            selectedSupplier: null,
            banks: [],
            selectedBank: null,
            useraccess: [],
            user_id: null,
        };
    },
    created() {
        this.user_id = localStorage.getItem("user_id");
        this.supplierpayment.added_by = this.user_id
        this.getSupplier();
        this.getBank();
        this.getSupplierPayment();
        this.getPermission();
        this.logOut();
    },
    methods: {
        getSupplier() {
            axios.get("/api/get_supplier").then((res) => {
                this.suppliers = res.data.suppliers;
                this.suppliers.unshift({ id: 0, display_name: 'Select Supplier' })
            });
        },
        getBank() {
            axios.get("/api/get_bankaccount").then((res) => {
                this.banks = res.data;
                this.banks.unshift({ id: 0, display_name: 'Select Bank' })
            });
        },
        getSupplierPayment() {
            axios.get("/api/get_supplierpayment").then((res) => {
                this.supplierpayments = res.data;
            });
        },

        onChangeSupplier() {
            this.supplierpayment.supplier_id = this.selectedCustomer.id
        },
        onChangeBank() {
            this.supplierpayment.bank_id = this.selectedBank.id
        },

        saveSupplierPayment(event) {
            if (this.selectedSupplier == null) {
                alert("Select Supplier Name");
                return;
            }
            if (this.supplierpayment.payment_amount == "") {
                alert("Payment amount is empty");
                return;
            }
            if (this.supplierpayment.payment_type == "bank") {
                if (this.selectedBank == null) {
                    alert("Please Select Bank account")
                }
            }
            this.supplierpayment.bank_id = this.selectedBank.id
            this.supplierpayment.supplier_id = this.selectedSupplier.id

            axios.post(location.origin + "/api/save_supplierpayment", this.supplierpayment)
                .then((res) => {
                    alert(res.data);
                    this.clearData();
                    this.getSupplierPayment();
                });
        },

        editRow(val) {
            this.supplierpayment = {
                id: val.id,
                date: val.date,
                payment_type: val.payment_type,
                bank_id: val.bank_id,
                supplier_id: val.supplier_id,
                due: val.due,
                payment_amount: val.payment_amount,
                description: val.description,
                added_by: val.added_by,
            };

            this.selectedSupplier = {
                id: val.supplier_id,
                display_name: `${val.supplier_code}-${val.name}`
            }
            this.selectedBank = {
                id: val.bank_id,
                display_name: `${val.account_no}-${val.bank_name}`
            }
        },

        deleteRow(id) {
            if (confirm("Are you sure")) {
                axios.get("/api/delete_supplierpayment/" + id).then((res) => {
                    alert(res.data);
                    this.getSupplierPayment();
                });
            }
        },

        clearData() {
            this.supplierpayment = {
                id: "",
                date: moment(new Date()).format("YYYY-MM-DD"),
                payment_type: "cash",
                bank_id: "",
                supplier_id: "",
                due: 0.00,
                payment_amount: 0.00,
                description: "",
            };
            this.selectedSupplier = null
            this.selectedBank = null
            this.getSupplierPayment()
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
            this.useraccess.includes("supplierpayment.index") ? "" : location.href = "/unauthorize"
        }
    },
    mounted() {
        document.title = "Supplier Payment Page";
    },
};
</script>
