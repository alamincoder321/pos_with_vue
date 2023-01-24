<template>
    <div class="container-fluid px-4">
        <div class="row">
            <div class="col-12 mt-3">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Bank Account Create</h4>
                    </div>
                    <form @submit.prevent="saveBankAccount">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 col-lg-5">
                                    <div class="row mt-2">
                                        <label for="account_name"
                                            class="col-5 col-lg-4 d-flex align-items-center">Account Name:</label>
                                        <div class="col-7 col-lg-8">
                                            <input type="text" id="account_name" name="account_name"
                                                class="form-control shadow-none" v-model="bankaccount.account_name"
                                                autocomplete="off" />
                                        </div>
                                    </div>                            
                                    <div class="row mt-2">
                                        <label for="account_no" class="col-5 col-lg-4 d-flex align-items-center">Account No.:</label>
                                        <div class="col-7 col-lg-8">
                                            <input type="text" id="account_no" name="account_no" class="form-control shadow-none"
                                                v-model="bankaccount.account_no" autocomplete="off" />
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <label for="account_type"
                                            class="col-5 col-lg-4 d-flex align-items-center">Account Type:</label>
                                        <div class="col-7 col-lg-8">
                                            <input type="text" id="account_type" name="account_type" class="form-control shadow-none"
                                                v-model="bankaccount.account_type" autocomplete="off" />
                                        </div>
                                    </div>                            
                                    <div class="row mt-2">
                                        <label for="status"
                                            class="col-5 col-lg-4 d-flex align-items-center">Status:</label>
                                        <div class="col-7 col-lg-8">
                                            <select id="status" name="status" class="form-control shadow-none"
                                                v-model="bankaccount.status">
                                                <option value="active">Active</option>
                                                <option value="inactive">Inactive</option>
                                            </select>
                                        </div>
                                    </div>                            
                                </div>
                                <div class="col-12 col-lg-5">
                                    <div class="row mt-2">
                                        <label for="bank_name"
                                            class="col-5 col-lg-4 d-flex align-items-center">Bank Name:</label>
                                        <div class="col-7 col-lg-8">
                                            <input type="text" id="bank_name" name="bank_name" class="form-control shadow-none"
                                                v-model="bankaccount.bank_name" autocomplete="off" />
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <label for="branch_name"
                                            class="col-5 col-lg-4 d-flex align-items-center">Branch Name:</label>
                                        <div class="col-7 col-lg-8">
                                            <input type="text" id="branch_name" name="branch_name" class="form-control shadow-none"
                                                v-model="bankaccount.branch_name" autocomplete="off" />
                                        </div>
                                    </div>                                   
                                    <div class="row mt-2">
                                        <label for="description"
                                            class="col-5 col-lg-4 d-flex align-items-center">Description:</label>
                                        <div class="col-7 col-lg-8">
                                            <textarea id="description" name="description" class="form-control shadow-none"
                                                v-model="bankaccount.description" autocomplete="off"></textarea>
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
                                    Save BankAccount
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <hr class="my-2" />
            <div class="col-12 col-lg-12" style="overflow-x:auto;">
                <vue-good-table :columns="columns" :rows="bankaccounts" :fixed-header="true" :pagination-options="{
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
export default {
    data() {
        return {
            columns: [
                {
                    label: "Account Name",
                    field: "account_name",
                },
                {
                    label: "Account No",
                    field: "account_no",
                },
                {
                    label: "Account Type",
                    field: "account_type",
                },
                {
                    label: "Bank Name",
                    field: "bank_name",
                },
                {
                    label: "Branch Name",
                    field: "branch_name",
                },
                {
                    label: "Status",
                    field: "status",
                },
                {
                    label: "Action",
                    field: "before",
                },
            ],
            bankaccounts: [],
            bankaccount: {
                id: "",
                account_name: "",
                account_no: "",
                bank_name: "",
                account_type: "",
                branch_name: "",
                status: "active",
                description: "",
            },
            useraccess: [],
            user_id: null,
        };
    },
    created() {
        this.user_id = localStorage.getItem("user_id");
        this.getBankAccount();
        this.getPermission();
        this.logOut();
    },
    methods: {
        getBankAccount() {
            axios.get("/api/get-bankaccount").then((res) => {
                this.bankaccounts = res.data;
            });
        },

        saveBankAccount(event) {
            if (this.bankaccount.account_name == "") {
                alert("Account Name Field is Empty");
                return;
            }
            if (this.bankaccount.account_no == "") {
                alert("Account Number Field is Empty");
                return;
            }
            if (this.bankaccount.account_type == "") {
                alert("Account Type Field is Empty");
                return;
            }
            if (this.bankaccount.bank_name == "") {
                alert("Bank Name Field is Empty");
                return;
            }
            if (this.bankaccount.branch_name == "") {
                alert("Branch Name Field is Empty");
                return;
            }

            let formdata = new FormData(event.target)
            formdata.append("id", this.bankaccount.id)
            axios
                .post(location.origin + "/api/save-bankaccount", formdata)
                .then((res) => {
                    this.$toastr.s(res.data, "Success!");
                    this.clearData();
                    this.getBankAccount();
                });
        },

        editRow(val) {
            this.bankaccount = {
                id: val.id,
                account_name: val.account_name,
                account_no: val.account_no,
                account_type: val.account_type,
                bank_name: val.bank_name,
                branch_name: val.branch_name,
                status: val.status,
                description: val.description,
            };
        },

        deleteRow(id) {
            if (confirm("Are you sure")) {
                axios.get("/api/delete-bankaccount/" + id).then((res) => {
                    this.$toastr.s(res.data, "Success!");
                    this.getBankAccount();
                });
            }
        },

        clearData() {
            this.bankaccount = {
                id: "",
                account_name: "",
                account_no: "",
                bank_name: "",
                account_type: "",
                branch_name: "",
                status: "active",
                description: "",
            };
            this.getBankAccount()
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
        document.title = "Bank Account Page";
    },
};
</script>
