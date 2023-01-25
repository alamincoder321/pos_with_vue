<template>
    <div class="container-fluid px-4">
        <div class="row">
            <div class="col-12 mt-3">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Expense Create</h4>
                    </div>
                    <form @submit.prevent="saveExpense">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 col-lg-5">
                                    <div class="row mt-2">
                                        <label for="expense_code"
                                            class="col-5 col-lg-4 d-flex align-items-center">Expense Code:</label>
                                        <div class="col-7 col-lg-8">
                                            <input type="text" id="expense_code" name="expense_code"
                                                class="form-control shadow-none" v-model="expense.expense_code"
                                                autocomplete="off" readonly/>
                                        </div>
                                    </div>                            
                                    <div class="row mt-2">
                                        <label for="date" class="col-5 col-lg-4 d-flex align-items-center">Date:</label>
                                        <div class="col-7 col-lg-8">
                                            <input type="date" id="date" name="date" class="form-control shadow-none"
                                                v-model="expense.date" autocomplete="off" />
                                        </div>
                                    </div>                           
                                </div>
                                <div class="col-12 col-lg-5">                               
                                    <div class="row mt-2">
                                        <label for="description"
                                            class="col-5 col-lg-4 d-flex align-items-center">Description:</label>
                                        <div class="col-7 col-lg-8">
                                            <textarea id="description" name="description" class="form-control shadow-none"
                                                v-model="expense.description" autocomplete="off"></textarea>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <label for="amount" class="col-5 col-lg-4 d-flex align-items-center">Amount:</label>
                                        <div class="col-7 col-lg-8">
                                            <input type="number" min="0" step="0.01" id="amount" name="amount" class="form-control shadow-none"
                                                v-model="expense.amount" autocomplete="off" />
                                        </div>
                                    </div>  
                                </div>                                
                            </div>
                            <div class="form-group text-center mt-3">
                                <button type="button" class="btn btn-outline-secondary btn-sm shadow-none"
                                    @click="clearData">
                                    Reset
                                </button>
                                <button :style="{display: useraccess.includes('bankaccount.store')?'':'none'}" type="submit" class="btn btn-sm btn-outline-success shadow-none">
                                    Save Expense
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <hr class="my-2" />
            <div class="col-12 col-lg-12" style="overflow-x:auto;">
                <vue-good-table :columns="columns" :rows="expenses" :fixed-header="true" :pagination-options="{
                    enabled: true,
                    perPage: 15,
                }" :search-options="{
    enabled: true,
}" :line-numbers="true" styleClass="vgt-table" max-height="550px">
                    <template slot="table-row" slot-scope="props">
                        <span v-if="props.column.field == 'before'">
                            <button :style="{display: useraccess.includes('bankaccount.edit')?'':'none'}" class="btn btn-sm btn-outline-primary shadow-none" @click="editRow(props.row)">
                                Edit
                            </button>
                            <button :style="{display: useraccess.includes('bankaccount.delete')?'':'none'}" class="btn btn-sm btn-outline-danger shadow-none" @click="deleteRow(props.row.id)">
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
            columns: [
                {
                    label: "Expense Code",
                    field: "expense_code",
                },
                {
                    label: "Date",
                    field: "date",
                },
                {
                    label: "Description",
                    field: "description",
                },
                {
                    label: "Amount",
                    field: "amount",
                },
                {
                    label: "Action",
                    field: "before",
                },
            ],
            expenses: [],
            expense: {
                id: "",
                expense_code: "",
                date: moment().format("YYYY-MM-DD"),
                description: "",
                amount: 0.00,
            },
            useraccess: [],
            user_id: null,
        };
    },
    created() {
        this.user_id = localStorage.getItem("user_id");
        this.getExpense();
        this.getPermission();
        this.logOut();
    },
    methods: {
        getExpense() {
            axios.get("/api/get-expense").then((res) => {
                this.expenses             = res.data.expenses;
                this.expense.expense_code = res.data.expense_code;
            });
        },

        saveExpense(event) {
            if (this.expense.description == "") {
                alert("Description Field is Empty");
                return;
            }
            if (this.expense.amount == "") {
                alert("Amount Field is Empty");
                return;
            }

            let formdata = new FormData(event.target)
            formdata.append("id", this.expense.id)
            axios
                .post(location.origin + "/api/save-expense", formdata)
                .then((res) => {
                    this.$toastr.s(res.data, "Success!");
                    this.clearData();
                    this.getExpense();
                });
        },

        editRow(val) {
            this.expense = {
                id: val.id,
                expense_code: val.expense_code,
                date: val.date,
                description: val.description,
                amount: val.amount,
            };
        },

        deleteRow(id) {
            if (confirm("Are you sure")) {
                axios.get("/api/delete-expense/" + id).then((res) => {
                    this.$toastr.s(res.data, "Success!");
                    this.getExpense();
                });
            }
        },

        clearData() {
            this.expense = {
                id: "",
                date: moment().format("YYYY-MM-DD"),
                description: "",
                amount: 0.00,
            };
            this.getExpense()
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
        useraccess(){
            this.useraccess.includes("bankaccount.index")?"":location.href = "/unauthorize"
        }
    },
    mounted() {
        document.title = "Expense Page";
    },
};
</script>
