<template>
    <div class="container-fluid px-4">
        <div class="row">
            <div class="col-12 mt-3">
                <div class="card" :style="{ display: useraccess.includes('damage.store') ? '' : 'none' }">
                    <div class="card-header">
                        <h4 class="card-title">Damage Entry</h4>
                    </div>
                    <form @submit.prevent="saveDamage">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 col-lg-5">
                                    <div class="row mt-2">
                                        <label for="damage_code"
                                            class="col-5 col-lg-4 d-flex align-items-center">Code:</label>
                                        <div class="col-7 col-lg-8">
                                            <input type="text" id="damage_code" name="damage_code"
                                                class="form-control shadow-none" v-model="damage.damage_code"
                                                readonly />
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <label for="date" class="col-5 col-lg-4 d-flex align-items-center">Date:</label>
                                        <div class="col-7 col-lg-8">
                                            <VueDatePicker v-model="damage.date" :style="color" />
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <label for="product_id"
                                            class="col-5 col-lg-4 d-flex align-items-center">Product:</label>
                                        <div class="col-7 col-lg-8">
                                            <div class="input-group">
                                                <v-select label="display_name" id="product" name="product_id"
                                                    :options="products" v-model="selectedProduct"
                                                    @input="onChangeProduct">
                                                </v-select>
                                                <a href="/products" target="_blank" class="btn btn-success shadow-none"
                                                    style="padding: 4px 8px;"><i class="fas fa-plus"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <label for="quantity"
                                            class="col-5 col-lg-4 d-flex align-items-center">Quantity:</label>
                                        <div class="col-7 col-lg-8">
                                            <input type="number" min="0" id="quantity" name="quantity"
                                                class="form-control shadow-none" v-model="damage.quantity" @input="calculateTotal" />
                                        </div>
                                    </div>

                                </div>
                                <div class="col-12 col-lg-5">
                                    <div class="row mt-2">
                                        <label for="price" class="col-5 col-lg-4 d-flex align-items-center">Price:</label>
                                        <div class="col-7 col-lg-8">
                                            <input type="number" min="0" id="price" name="price" class="form-control shadow-none"
                                                v-model="damage.price" autocomplete="off" />
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <label for="total_amount" class="col-5 col-lg-4 d-flex align-items-center">Total Amount:</label>
                                        <div class="col-7 col-lg-8">
                                            <input type="number" min="0" id="total_amount" name="total_amount" class="form-control shadow-none"
                                                v-model="damage.total_amount" readonly />
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <label for="description"
                                            class="col-5 col-lg-4 d-flex align-items-center">Description</label>
                                        <div class="col-7 col-lg-8">
                                            <textarea id="description" name="description"
                                                class="form-control shadow-none" v-model="damage.description"
                                                autocomplete="off"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-lg-2 d-flex justify-content-center align-items-center">
                                    <div class="card" style="border-radius:0;width: 75% !important;">
                                        <div class="card-header text-center">Stock</div>
                                        <div class="card-body text-center">2</div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group text-center mt-3">
                                <button type="button" class="btn btn-outline-secondary btn-sm shadow-none"
                                    @click="clearData">
                                    Reset
                                </button>
                                <button :style="{ display: useraccess.includes('damage.store') ? '' : 'none' }"
                                    type="submit" class="btn btn-sm btn-outline-success shadow-none">
                                    Save Damage
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <hr class="my-2" />
            <div class="col-12 col-lg-12">
                <vue-good-table :columns="columns" :rows="damages" :fixed-header="true" :pagination-options="{
                    enabled: true,
                    perPage: 15,
                }" :search-options="{
    enabled: true,
}" :line-numbers="true" styleClass="vgt-table" max-height="550px">
                    <template slot="table-row" slot-scope="props">
                        <span v-if="props.column.field == 'before'">
                            <button :style="{ display: useraccess.includes('damage.edit') ? '' : 'none' }"
                                class="btn btn-sm btn-outline-primary shadow-none" @click="editRow(props.row)">
                                Edit
                            </button>
                            <button :style="{ display: useraccess.includes('damage.delete') ? '' : 'none' }"
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
                    label: "Code",
                    field: "damage_code",
                },
                {
                    label: "Date",
                    field: "date",
                },
                {
                    label: "Product",
                    field: "name",
                },
                {
                    label: "Qty",
                    field: "quantity",
                },
                {
                    label: "Price",
                    field: "price",
                },
                {
                    label: "Total",
                    field: "total_amount",
                },
                {
                    label: "Action",
                    field: "before",
                },
            ],
            damages: [],
            products: [],
            selectedProduct: null,
            damage: {
                id: "",
                damage_code: "",
                date: moment(new Date()).format("YYYY-MM-DD"),
                product_id: "",
                quantity: 0,
                price: 0,
                total_amount: 0,
                description: "",
            },
            user_id: null,
            useraccess: [],
        };
    },
    created() {
        this.user_id = localStorage.getItem("user_id");
        this.getDamage();
        this.getProduct();
        this.getPermission();
        this.logOut();
    },
    methods: {
        getDamage() {
            axios.get("/api/get_damage").then((res) => {
                this.damage.damage_code = res.data.gen_code;
                this.damages = res.data.damages;
            });
        },
        getProduct() {
            axios.get("/api/get_product").then((res) => {
                this.products = res.data.products;
                this.products.unshift({ id: 0, display_name: "Select Product" })
            });
        },

        onChangeProduct() {
            this.damage.product_id = this.selectedProduct.id;
            this.damage.price      = this.selectedProduct.purchase_price;
        },

        calculateTotal(){
            this.damage.total_amount = (parseFloat(this.damage.price) * this.damage.quantity).toFixed(2);
        },

        saveDamage(event) {
            if (this.selectedProduct.id == "") {
                alert("Product Field is Empty");
                document.querySelector("#product [type='search']").focus()
                return;
            }

            axios
                .post(location.origin + "/api/save_damage", this.damage)
                .then((res) => {
                    alert(res.data);
                    this.clearData();
                    this.getDamage();
                });
        },

        editRow(val) {
            this.damage = {
                id: val.id,
                damage_code: val.damage_code,
                date: val.date,
                product_id: val.product_id,
                quantity: val.quantity,
                price: val.price,
                total_amount: val.total_amount,
                description: val.description,
            };
            this.selectedProduct = {
                id: val.product_id,
                display_name: val.display_name
            }
        },

        deleteRow(id) {
            if (confirm("Are you sure")) {
                axios.get("/api/delete_damage/" + id).then((res) => {
                    alert(res.data);
                    this.getDamage();
                });
            }
        },

        clearData() {
            this.damage = {
                id: "",
                damage_code: "",
                date: moment(new Date()).format("YYYY-MM-DD"),
                product_id: "",
                quantity: 0,
                price: 0,
                total_amount: 0,
                description: "",
            };
            this.selectedProduct = null
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
            this.useraccess.includes("damage.index") ? "" : location.href = "/unauthorize"
        }
    },
    mounted() {
        document.title = "Damage Page";
    },
};
</script>
