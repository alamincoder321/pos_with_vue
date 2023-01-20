<template>
    <div class="container-fluid px-4">
        <div class="row">
            <div class="col-12 mt-3">
                <div class="card" :style="{ display: useraccess.includes('transaction.store') ? '' : 'none' }">
                    <div class="card-header">
                        <h4 class="card-title">Transaction Create</h4>
                    </div>
                    <form @submit.prevent="saveTransaction">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 col-lg-9">
                                    <div class="row mt-2">
                                        <label for="date" class="col-5 col-lg-4 d-flex align-items-center">Date:</label>
                                        <div class="col-7 col-lg-8">
                                            <input type="date" id="date" name="date" class="form-control shadow-none"
                                                v-model="transaction.date" autocomplete="off" />
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <label for="transaction_type"
                                            class="col-5 col-lg-4 d-flex align-items-center">Transaction Type:</label>
                                        <div class="col-7 col-lg-8">
                                            <select id="transaction_type" name="transaction_type" class="form-control shadow-none"
                                                v-model="transaction.transaction_type">
                                                <option value="deposit">Deposit</option>
                                                <option value="withdraw">Withdraw</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <label for="account_id"
                                            class="col-5 col-lg-4 d-flex align-items-center">Bank Name:</label>
                                        <div class="col-7 col-lg-8">
                                            <v-select :options="accounts" id="account" label="display_name" v-model="selectedAccount" @input="onChangeAccount"></v-select>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <label for="amount" class="col-5 col-lg-4 d-flex align-items-center">Amount:</label>
                                        <div class="col-7 col-lg-8">
                                            <input type="number" min="0" id="amount" name="amount"
                                                class="form-control shadow-none" v-model="transaction.amount"
                                                autocomplete="off" />
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <label for="description"
                                            class="col-5 col-lg-4 d-flex align-items-center">Description:</label>
                                        <div class="col-7 col-lg-8">
                                            <textarea id="description" name="description" class="form-control shadow-none"
                                                v-model="transaction.description" autocomplete="off"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-lg-3 d-flex justify-content-center align-items-center">
                                    <div class="card" style="width: 80%;" :style="{display: balance >= 0 ? '':'none'}">
                                        <div class="card-header text-center bg-secondary text-white">
                                            Balance
                                        </div>
                                        <div class="card-body text-center">
                                            {{balance}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group text-center mt-3">
                                <button type="button" class="btn btn-outline-secondary btn-sm shadow-none"
                                    @click="clearData">
                                    Reset
                                </button>
                                <button :style="{ display: useraccess.includes('transaction.store') ? '' : 'none' }" type="submit"
                                    class="btn btn-sm btn-outline-success shadow-none">
                                    Save Transaction
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <hr class="my-2" />
            <div class="col-12 col-lg-12" style="overflow-x:auto;">
                <vue-good-table :columns="columns" :rows="transactions" :fixed-header="true" :pagination-options="{
                    enabled: true,
                    perPage: 15,
                }" :search-options="{
    enabled: true,
}" :line-numbers="true" styleClass="vgt-table" max-height="550px">
                    <template slot="table-row" slot-scope="props">
                        <span v-if="props.column.field == 'before'">
                            <button :style="{ display: useraccess.includes('transaction.edit') ? '' : 'none' }"
                                class="btn btn-sm btn-outline-primary shadow-none" @click="editRow(props.row)">
                                Edit
                            </button>
                            <button :style="{ display: useraccess.includes('transaction.delete') ? '' : 'none' }"
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
import axios from 'axios';

var moment = require('moment');
export default {
    data() {
        return {
            columns: [
                {
                    label: "Date",
                    field: "date",
                },
                {
                    label: "Transaction Type",
                    field: "transaction_type",
                },
                {
                    label: "Bank Name",
                    field: "bank_name",
                },
                {
                    label: "Amount",
                    field: "amount",
                },
                {
                    label: "Description",
                    field: "description",
                },
                {
                    label: "Action",
                    field: "before",
                },
            ],
            transactions: [],
            transaction: {
                date: moment(new Date()).format("YYYY-MM-DD"),
                transaction_type: "deposit",
                account_id: "",
                amount: "",
                description: "",
            },
            accounts: [],
            selectedAccount: null,
            balance: 0,
            user_id: null,
            useraccess: [],
        };
    },
    created() {
        this.user_id = localStorage.getItem("user_id");
        this.getTransaction();
        this.getBank();
        this.logOut();
        this.getPermission();
    },
    methods: {
        getBank() {
            axios.get("/api/get-bankaccount").then((res) => {
                this.accounts = res.data;
            });
        },
        getTransaction() {
            axios.get("/api/get-transaction").then((res) => {
                this.transactions = res.data;
            });
        },

        onChangeAccount(){
            if(this.selectedAccount == null){
                this.balance = 0
                return
            }
            this.transaction.account_id = this.selectedAccount.id
            axios.post(location.origin+"/api/getbank-balance", {id: this.selectedAccount.id})
                .then(res => {
                    this.balance = res.data[0].totalbalance
                })
        },

        saveTransaction(event) {
            if (this.transaction.amount == "") {
                alert("Amount field is Empty");
                return;
            }
            if (this.selectedAccount == null) {
                alert("Select Bank Account");
                document.querySelector("#account [type='search']").focus()
                return;
            }
            if(this.transaction.transaction_type == 'withdraw'){
                if(this.balance == 0 || parseFloat(this.balance) < this.transaction.amount){
                    alert("You have insuficiant balance")
                    document.querySelector("#amount").style.color = "red"
                    return
                }
            }

            axios
                .post(location.origin + "/api/save-transaction", this.transaction)
                .then((res) => {
                    alert(res.data);
                    this.clearData();
                    this.getTransaction();
                });
        },

        editRow(val) {
            this.transaction = {
                id: val.id,
                date: val.date,
                transaction_type: val.transaction_type,
                account_id: val.account_id,
                amount: val.amount,
                description: val.description,
            };
            this.selectedAccount = {
                id: val.account_id,
                display_name: val.display_name
            }
            axios.post(location.origin+"/api/getbank-balance", {id: this.selectedAccount.id})
                .then(res => {
                    this.balance = res.data[0].totalbalance
                })
        },

        deleteRow(id) {
            if (confirm("Are you sure")) {
                axios.get("/api/delete-transaction/" + id).then((res) => {
                    alert(res.data);
                    this.getTransaction();
                });
            }
        },

        clearData() {
            this.transaction = {
                id: "",
                date: moment(new Date()).format("YYYY-MM-DD"),
                transaction_type: "deposit",
                account_id: "",
                amount: "",
                description: "",
            };
            this.balance = 0
            document.querySelector("#amount").style.color = "black"
            this.selectedAccount = null
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
            this.useraccess.includes("transaction.index") ? "" : location.href = "/unauthorize"
        }
    },

    mounted() {
        document.title = "Transaction Page";
    },
};
</script>
