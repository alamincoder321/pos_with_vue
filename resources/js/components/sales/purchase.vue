<template>
    <div class="container-fluid px-4 mt-2">
        <div class="row">
            <div class="col-12">
                <div class="card" style="border-bottom:none;">
                    <div class="card-header">
                        <div class="row">
                            <label for="invoice" class="col-4 col-lg-1">Invoice:</label>
                            <div class="col-8 col-lg-2">
                                <input type="number" readonly class="form-control shadow-none">
                            </div>

                            <label for="category" class="col-4 col-lg-2">Category:</label>
                            <div class="col-8 col-lg-3">
                                <v-select label="name" id="category" name="category_id" :options="categories"
                                    v-model="selectedCategory"></v-select>
                            </div>

                            <label for="invoice" class="col-4 col-lg-1">Brand:</label>
                            <div class="col-8 col-lg-3">
                                <v-select label="name" id="brand" name="brand_id" :options="brands"
                                    v-model="selectedBrand">
                                </v-select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-lg-9">
                <div class="card mt-2">
                    <div class="card-header">
                        <h4>Supplier && Product Information</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 col-lg-6">
                                <div class="row mt-2">
                                    <label for="supplier_id"
                                        class="col-5 col-lg-4 d-flex align-items-center">Supplier:</label>
                                    <div class="col-7 col-lg-8">
                                        <div class="input-group">
                                            <v-select label="display_name" id="supplier" name="supplier_id"
                                                :options="suppliers" v-model="selectedSupplier"
                                                @input="onChangeSupplier"></v-select>
                                            <a href="/suppliers" target="_blank" class="btn btn-success shadow-none"
                                                style="padding: 4px 6px;"><i class="fas fa-plus"></i></a>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-2"
                                    :style="{ display: selectedSupplier.supplier_type != 'G' ? 'none' : '' }">
                                    <label for="name" class="col-5 col-lg-4 d-flex align-items-center">Name:</label>
                                    <div class="col-7 col-lg-8">
                                        <input type="text" id="name" name="name" class="form-control shadow-none"
                                            v-model="selectedSupplier.name" autocomplete="off"
                                            :readonly="selectedSupplier.supplier_type != 'G' ? true : false" />
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <label for="phone" class="col-5 col-lg-4 d-flex align-items-center">Phone:</label>
                                    <div class="col-7 col-lg-8">
                                        <input type="text" id="phone" name="phone" class="form-control shadow-none"
                                            v-model="selectedSupplier.phone" autocomplete="off"
                                            :readonly="selectedSupplier.supplier_type != 'G' ? true : false" />
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <label for="address"
                                        class="col-5 col-lg-4 d-flex align-items-center">Address:</label>
                                    <div class="col-7 col-lg-8">
                                        <textarea id="address" name="address" class="form-control shadow-none"
                                            v-model="selectedSupplier.address" autocomplete="off"
                                            :readonly="selectedSupplier.supplier_type != 'G' ? true : false"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-lg-6">
                                <div class="row mt-2">
                                    <label for="product_id"
                                        class="col-5 col-lg-4 d-flex align-items-center">Product:</label>
                                    <div class="col-7 col-lg-8">
                                        <div class="input-group">
                                            <v-select label="display_name" id="product" name="product_id"
                                                :options="products" v-model="selectedProduct" @input="onChangeProduct">
                                            </v-select>
                                            <a href="/products" target="_blank" class="btn btn-success shadow-none"
                                                style="padding: 4px 6px;"><i class="fas fa-plus"></i></a>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <label for="purchase_price"
                                        class="col-5 col-lg-4 d-flex align-items-center">Pur.Rate:</label>
                                    <div class="col-7 col-lg-3 pe-lg-0">
                                        <input type="text" id="purchase_price" name="purchase_price"
                                            class="form-control shadow-none" @input="cartQtyPurchaseChange"
                                            v-model="cart.purchase_price" autocomplete="off" />
                                    </div>
                                    <label for="qty" class="col-5 col-lg-1 d-flex align-items-center">Qty:</label>
                                    <div class="col-7 col-lg-4">
                                        <input type="number" id="qty" name="qty" v-model="cart.qty"
                                            @input="cartQtyPurchaseChange" class="form-control shadow-none"
                                            autocomplete="off" />
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <label for="selling_price" class="col-5 col-lg-4 d-flex align-items-center">Sale
                                        Rate:</label>
                                    <div class="col-7 col-lg-8">
                                        <input type="number" id="selling_price" name="selling_price"
                                            class="form-control shadow-none" v-model="cart.selling_price"
                                            autocomplete="off" />
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <label for="total_amount" class="col-5 col-lg-4 d-flex align-items-center">Total
                                        Amount:</label>
                                    <div class="col-7 col-lg-8">
                                        <input type="number" id="total_amount" name="total_amount"
                                            v-model="cart.total_amount" class="form-control shadow-none"
                                            autocomplete="off" readonly />
                                    </div>
                                </div>
                                <div class="form-group text-end mt-2">
                                    <button class="btn btn-primary shadow-none" @click="AddToCart">AddToCart</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card mt-2 border-0">
                    <div class="card-body p-lg-0">
                        <table class="table table-bordered">
                            <thead style="background: gray;color: white;">
                                <tr>
                                    <th class="text-center">Sl</th>
                                    <th class="text-center">Product Name</th>
                                    <th class="text-center">Purchase Rate</th>
                                    <th class="text-center">Quantity</th>
                                    <th class="text-center">Total Amount</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-3">
                <div class="card mt-2">
                    <div class="card-header">
                        <h4>Amount Details</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="date">Date:</label>
                                    <date-picker id="date" name="date" class="form-control shadow-none" v-model="date"
                                        :config="options"></date-picker>
                                </div>
                                <div class="form-group">
                                    <label for="subtotal">SubTotal:</label>
                                    <input type="number" id="subtotal" class="form-control shadow-none" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="vat">Vat:</label>
                                    <div class="row">
                                        <div class="col-6 col-lg-7">
                                            <div class="input-group">
                                                <input type="number" style="height:32px;" id="vat"
                                                    class="form-control shadow-none"><span
                                                    style="height:32px;line-height:1;" class="btn btn-warning">%</span>
                                            </div>
                                        </div>
                                        <div class="col-6 col-lg-5">
                                            <input type="number" id="vat" class="form-control shadow-none" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="discount">Discount:</label>
                                    <input type="number" id="discount" class="form-control shadow-none">
                                </div>
                                <div class="form-group">
                                    <label for="transposrt_cost">Labour Cost/Transport Cost:</label>
                                    <input type="number" id="transposrt_cost" class="form-control shadow-none">
                                </div>
                                <div class="form-group">
                                    <label for="total">Total:</label>
                                    <input type="number" id="total" class="form-control shadow-none" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="payment_type">Payment Type:</label>
                                    <select name="payment_type" id="payment_type" class="form-control shadow-none">
                                        <option value="cash">Cash</option>
                                        <option value="bank">Bank</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="paid">Paid:</label>
                                    <input type="number" id="paid" class="form-control shadow-none">
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="due">Due:</label>
                                            <input type="text" id="due" class="form-control shadow-none" readonly>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="previous_due">Pre.Due:</label>
                                            <input type="text" id="previous_due" class="form-control shadow-none"
                                                readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-5">
                                        <button class="btn btn-secondary shadow-none w-100">Reset</button>
                                    </div>
                                    <div class="col-7">
                                        <button class="btn btn-success shadow-none w-100">Purchase</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {

    data() {
        return {
            options: {
                format: 'DD/MM/YYYY',
                useCurrent: true,
            },
            date: new Date(),
            categories: [],
            selectedCategory: {
                id: "",
                name: ""
            },
            brands: [],
            selectedBrand: {
                id: "",
                name: ""
            },
            suppliers: [],
            selectedSupplier: {
                id: "",
                display_name: "",
                name: "",
                phone: "",
                address: "",
                supplier_type: ""
            },
            products: [],
            selectedProduct: {
                id: "",
                display_name: "",
                name: "",
            },

            cart: {
                product_id: "",
                product_name: "",
                purchase_price: 0,
                qty: 0,
                selling_price: 0,
                total_amount: 0,
            },
            useraccess: [],
            user_id: null,
        }
    },
    created() {
        this.user_id = localStorage.getItem("user_id");
        this.getCategory();
        this.getBrand();
        this.getSupplier();
        this.getProduct();
        this.getPermission();
        this.logOut();
    },
    methods: {
        getCategory() {
            axios.get("/api/get_category").then((res) => {
                this.categories = res.data;
                this.categories.unshift({ id: 0, name: "Select Category" })
            });
        },
        getBrand() {
            axios.get("/api/get_brand").then((res) => {
                this.brands = res.data;
                this.brands.unshift({ id: 0, name: "Select Brand" })
            });
        },
        getSupplier() {
            axios.get("/api/get_supplier").then((res) => {
                this.suppliers = res.data.suppliers;
                this.suppliers.unshift({ id: 0, display_name: "General Supplier", supplier_type: "G" })
            });
        },
        getProduct() {
            axios.get("/api/get_product").then((res) => {
                this.products = res.data.products;
                this.products.unshift({ id: 0, display_name: "Select Product" })
            });
        },

        onChangeSupplier() {
            if (this.selectedSupplier == null) {
                this.selectedSupplier = {
                    id: "",
                    name: "",
                    display_name: "",
                    phone: "",
                    address: "",
                    supplier_type: ""
                }
                return
            }
        },
        onChangeProduct() {
            if (this.selectedProduct == null) {
                this.selectedProduct = {
                    id: "",
                    display_name: "",
                    name: "",
                    purchase_price: "",
                    selling_price: "",
                }
                return
            }

            if (this.selectedProduct.id != "" || this.selectedProduct.id != 0) {
                this.cart = {
                    product_id: this.selectedProduct.id,
                    product_name: this.selectedProduct.name,
                    purchase_price: this.selectedProduct.purchase_price,
                    qty: 0,
                    selling_price: this.selectedProduct.selling_price,
                    total_amount: 0,
                }
            }
        },

        cartQtyPurchaseChange() {
            this.cart.total_amount = (this.cart.qty * this.cart.purchase_price).toFixed(2)
        },

        AddToCart() {
            alert("Hello Cart");
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
            this.useraccess.includes("dashboard") ? "" : location.href = "/unauthorize"
        }
    },
    mounted() {
        document.title = "Perchase Page"
    },
};
</script>


<style>
#category {
    width: 100% !important;
}

#brand {
    width: 100% !important;
}

#supplier {
    width: 87% !important;
}

#product {
    width: 87% !important;
}


.vs__selected-options {
    overflow: hidden;
}

.vs__selected {
    position: absolute;
    top: 0px;
    left: 3px;
}

#vs3__listbox {
    width: 300px !important;
}

#vs4__listbox {
    width: 300px !important;
}

@media screen and (min-device-width: 360px) and (max-device-width: 768px) {

    #supplier {
        width: 81% !important;
    }

    #product {
        width: 81% !important;
    }
}
</style>