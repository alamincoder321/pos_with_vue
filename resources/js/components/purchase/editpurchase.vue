<template>
    <div class="container-fluid px-4 mt-2">
        <div class="row">
            <div class="col-12">
                <div class="card" style="border-bottom:none;">
                    <div class="card-header">
                        <div class="row">
                            <label for="invoice" class="col-4 col-lg-1 d-flex align-items-center m-0">Invoice:</label>
                            <div class="col-8 col-lg-2">
                                <input type="text" v-model="purchase.invoice" readonly class="form-control shadow-none">
                            </div>

                            <label for="category" class="col-4 col-lg-2 d-flex align-items-center m-0">Category:</label>
                            <div class="col-8 col-lg-3">
                                <v-select label="name" id="category" name="category_id" :options="categories"
                                    v-model="selectedCategory" @input="CategoryChange"></v-select>
                            </div>

                            <label for="brand" class="col-4 col-lg-1 d-flex align-items-center m-0">Brand:</label>
                            <div class="col-8 col-lg-3">
                                <v-select label="name" @input="BrandChange" id="brand" name="brand_id" :options="brands"
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
                                                style="padding: 4px 6px;width: 13%;"><i class="fas fa-plus"></i></a>
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
                                                style="padding: 4px 6px;width: 13%;"><i class="fas fa-plus"></i></a>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <label for="purchase_price"
                                        class="col-5 col-lg-4 d-flex align-items-center">Pur.Rate:</label>
                                    <div class="col-7 col-lg-3 pe-lg-0">
                                        <input type="text" id="purchase_price" name="purchase_price"
                                            class="form-control shadow-none" @input="cartQtyPurchaseChange"
                                            v-model="selectedProduct.purchase_price" autocomplete="off" />
                                    </div>
                                    <label for="quantity" class="col-5 col-lg-1 d-flex align-items-center">Qty:</label>
                                    <div class="col-7 col-lg-4">
                                        <input type="number" id="quantity" name="quantity"
                                            v-model="selectedProduct.quantity" @input="cartQtyPurchaseChange"
                                            class="form-control shadow-none" autocomplete="off" />
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <label for="selling_price" class="col-5 col-lg-4 d-flex align-items-center">Sale
                                        Rate:</label>
                                    <div class="col-7 col-lg-8">
                                        <input type="number" id="selling_price" name="selling_price"
                                            class="form-control shadow-none" v-model="selectedProduct.selling_price"
                                            autocomplete="off" />
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <label for="total_amount" class="col-5 col-lg-4 d-flex align-items-center">Total
                                        Amount:</label>
                                    <div class="col-7 col-lg-8">
                                        <input type="number" id="total_amount" name="total_amount"
                                            v-model="selectedProduct.total_amount" class="form-control shadow-none"
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
                    <div class="card-body p-lg-0" style="overflow-x:auto;">
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
                            <tbody>
                                <tr v-for="(item, index) in carts" :key="index">
                                    <td class="text-center">{{ index + 1 }}</td>
                                    <td class="text-center">{{ item.name }}</td>
                                    <td class="text-center">{{ item.purchase_price }}</td>
                                    <td class="text-center">{{ item.quantity }}</td>
                                    <td class="text-center">{{ item.total_amount }}</td>
                                    <td class="text-center">
                                        <button @click="removeCart(item)"
                                            class="btn shadow-none btn-sm btn-danger border-0"
                                            style="border-radius: 0;">remove</button>
                                    </td>
                                </tr>
                                <tr v-if="carts.length != 0">
                                    <td colspan="4" class="text-center">
                                        <div class="form-group row">
                                            <label for="note" class="col-5">Note:</label>
                                            <div class="col-7">
                                                <textarea name="note" id="note" v-model="purchase.note"
                                                    class="form-control shadow-none"></textarea>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center" colspan="2" style="font-weight: bold;"><span>Total:
                                        </span>{{ carts.reduce((acc,
                                                c) => { return +acc + +c.total_amount }, 0).toFixed(2)
                                        }}</td>
                                </tr>
                            </tbody>
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
                            <form @submit.prevent="savePurchase">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="date">Date:</label>
                                        <VueDatePicker v-model="purchase.date" :style="color" />
                                    </div>
                                    <div class="form-group">
                                        <label for="subtotal">SubTotal:</label>
                                        <input type="number" id="subtotal" name="subtotal" v-model="purchase.subtotal"
                                            class="form-control shadow-none" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label for="vat">Vat:</label>
                                        <div class="row">
                                            <div class="col-6 col-lg-7">
                                                <div class="input-group">
                                                    <input type="number" min="0" style="height:30px;" id="vat" name="vat"
                                                        @input="TotalAmount" v-model="purchase.vat"
                                                        class="form-control shadow-none"><span
                                                        style="height:30px;line-height:1;"
                                                        class="btn btn-warning">%</span>
                                                </div>
                                            </div>
                                            <div class="col-6 col-lg-5">
                                                <input type="text" id="vat_amount" name="vat_amount"
                                                    v-model="purchase.vat_amount" class="form-control shadow-none"
                                                    readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="discount">Discount:</label>
                                        <div class="row">
                                            <div class="col-6 col-lg-7">
                                                <div class="input-group">
                                                    <input type="number" min="0" style="height:30px;" id="discount"
                                                        @input="TotalAmount" name="discount" v-model="purchase.discount"
                                                        class="form-control shadow-none"><span
                                                        style="height:30px;line-height:1;"
                                                        class="btn btn-warning">%</span>
                                                </div>
                                            </div>
                                            <div class="col-6 col-lg-5">
                                                <input type="text" id="discount_amount" name="discount_amount"
                                                    v-model="purchase.discount_amount" class="form-control shadow-none"
                                                    readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="transport_cost">Labour Cost/Transport Cost:</label>
                                        <input type="number" min="0" id="transport_cost" name="transport_cost"
                                            @input="TotalAmount" v-model="purchase.transport_cost"
                                            class="form-control shadow-none">
                                    </div>
                                    <div class="form-group">
                                        <label for="total">Total:</label>
                                        <input type="number" id="total" name="total" v-model="purchase.total"
                                            class="form-control shadow-none" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label for="payment_type">Payment Type:</label>
                                        <select name="payment_type" id="payment_type" v-model="purchase.payment_type"
                                            class="form-control shadow-none" @change="paymentChange">
                                            <option value="cash">Cash</option>
                                            <option value="bank">Bank</option>
                                        </select>
                                    </div>
                                    <div class="form-group" :style="{display: purchase.payment_type == 'bank'?'':'none'}">
                                        <label for="account_id">Bank Name:</label>
                                        <v-select label="display_name" @input="AccountChange" id="account" name="account_id"
                                            :options="accounts" v-model="selectedAccount">
                                        </v-select>
                                    </div>
                                    <div class="form-group">
                                        <label for="paid">Paid:</label>
                                        <input type="number" min="0" step="0.01" id="paid" name="paid" @input="TotalAmount"
                                            v-model="purchase.paid" class="form-control shadow-none" :readonly="selectedSupplier.supplier_type =='G'?true:false">
                                    </div>
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="due">Due:</label>
                                                <input type="text" id="due" name="due" v-model="purchase.due"
                                                    class="form-control shadow-none"
                                                    :style="{ color: purchase.due < 0 ? 'red' : 'black' }" readonly>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="previous_due">Pre.Due:</label>
                                                <input type="text" id="previous_due" :value="purchase.previous_due"
                                                    :style="{ color: purchase.previous_due != 0 ? 'red' : 'black' }"
                                                    class="form-control shadow-none" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-5">
                                            <button type="button" class="btn btn-secondary shadow-none w-100">Reset</button>
                                        </div>
                                        <div class="col-7">
                                            <button class="btn btn-success shadow-none w-100">Purchase</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
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
            color: {
                background: '#ffadb4',
                borderRadius: '0.2rem',
                height: '28px',
            },
            categories: [],
            selectedCategory: {
                id: "",
                name: "Select Category"
            },
            brands: [],
            selectedBrand: {
                id: "",
                name: "Select Brand"
            },
            suppliers: [],
            selectedSupplier: {
                id: "",
                display_name: "Select Suplier",
                name: "",
                phone: "",
                address: "",
                supplier_type: "",
                previous_due: 0,
            },
            accounts: [],
            selectedAccount: {
                id: "",
                display_name: "",
            },
            products: [],
            products1: [],
            selectedProduct: {
                id: '',
                product_code: '',
                display_name: 'Select Product',
                name: '',
                quantity: '',
                purchase_price: '',
                selling_price: 0.00,
                total_amount: ''
            },
            carts: [],
            purchase: {
                id: "",
                date: moment().format("YYYY-MM-DD"),
                subtotal: 0,
                total: 0,
                paid: 0,
                vat: 0,
                vat_amount: 0,
                discount: 0,
                previous_due: 0,
                discount_amount: 0,
                transport_cost: 0,
                payment_type: 'cash',
                due: 0,
                invoice: "",
                note: "",
                added_by: "",
                account_id: "",
            },
            useraccess: [],
            user_id: null,
        }
    },
    created() {
        this.user_id = localStorage.getItem("user_id");
        this.purchase.added_by = this.user_id;
        this.getPurchase();
        this.getCategory();
        this.getBrand();
        this.getSupplier();
        this.getBank();
        this.getProduct();
        this.getPermission();
        this.logOut();
    },

    methods: {
        getBank() {
            axios.get("/api/get-bankaccount").then((res) => {
                this.accounts = res.data;
                this.accounts.unshift({ id: 0, display_name: "Select Bank" })
            });
        },
        getCategory() {
            axios.get("/api/get-category").then((res) => {
                this.categories = res.data;
                this.categories.unshift({ id: 0, name: "Select Category" })
            });
        },
        getBrand() {
            axios.get("/api/get-brand").then((res) => {
                this.brands = res.data;
                this.brands.unshift({ id: 0, name: "Select Brand" })
            });
        },
        getSupplier() {
            axios.get("/api/get-supplier").then((res) => {
                this.suppliers = res.data.suppliers.filter(s => s.supplier_type != "G");
                this.suppliers.unshift({ id: 0, display_name: "General Supplier", supplier_type: "G" })
            });
        },
        getProduct() {
            axios.get("/api/get-product").then((res) => {
                this.products = res.data.products;
                this.products1 = res.data.products;
                this.products.unshift({ id: 0, display_name: "Select Product" })
            });
        },
        getPurchase() {
            let data = { invoice: this.$route.params.id }
            axios.post("/api/get-purchase", data).then((res) => {
                this.purchase = res.data.purchases[0]
                this.carts = res.data.purchases[0].purchaseDetails
                if(res.data.purchases[0].account_id){
                    this.selectedAccount = {
                        id: res.data.purchases[0].account_id,
                        display_name: res.data.purchases[0].bank_display_name
                    }
                }
                if (res.data.purchases[0].supplier_type == "G") {
                    this.selectedSupplier = {
                        id: res.data.purchases[0].supplier_id,
                        name: res.data.purchases[0].name,
                        display_name: "General Supplier",
                        phone: res.data.purchases[0].phone,
                        address: res.data.purchases[0].address,
                        supplier_type: res.data.purchases[0].supplier_type
                    }
                } else {
                    this.selectedSupplier = {
                        id: res.data.purchases[0].supplier_id,
                        name: res.data.purchases[0].name,
                        display_name: res.data.purchases[0].display_name,
                        phone: res.data.purchases[0].phone,
                        address: res.data.purchases[0].address,
                        supplier_type: res.data.purchases[0].supplier_type
                    }
                }
            });
        },
        AccountChange(){
            this.purchase.account_id = this.selectedAccount.id
        },
        paymentChange(){
            if(this.purchase.payment_type == "cash"){
                this.selectedAccount = {
                    id: "",
                    display_name: ""
                }
                this.purchase.account_id = ""
            }
        },
        onChangeSupplier() {
            if (this.selectedSupplier == null) {
                this.selectedSupplier = {
                    id: "",
                    name: "",
                    display_name: "",
                    phone: "",
                    address: "",
                    supplier_type: "",
                    previous_due: 0,
                }
                return
            }
            if (this.selectedSupplier.id == "") {
                this.purchase.previous_due = 0
                this.TotalAmount()
                return
            }
            axios.post("/api/get-supduetotal", { id: this.selectedSupplier.id }).then((res) => {
                this.purchase.previous_due = res.data[0].dueAmount
            });
        },
        onChangeProduct() {
            if (this.selectedProduct == null) {
                this.selectedProduct = {
                    id: "",
                    display_name: "",
                    name: "",
                    quantity: "",
                    purchase_price: "",
                    selling_price: "",
                }
                return
            }
        },

        cartQtyPurchaseChange() {
            this.selectedProduct.total_amount = (this.selectedProduct.quantity * this.selectedProduct.purchase_price).toFixed(2)
        },

        AddToCart() {
            if (this.selectedProduct.product_id != "") {
                let cartInd = this.carts.findIndex(p => p.product_id == this.selectedProduct.id);
                if (cartInd > -1) {
                    this.carts.splice(cartInd, 1)
                }
                if (this.selectedProduct.id == "") {
                    alert("Must be select product")
                    document.querySelector("#product [type='search']").focus()
                    return
                }
                if (this.selectedProduct.quantity == undefined) {
                    alert("Quantity increment must")
                    document.querySelector("#quantity").focus()
                    return
                }
                
                this.product = {
                    product_id: this.selectedProduct.id,
                    name: this.selectedProduct.name,
                    purchase_price: this.selectedProduct.purchase_price,
                    selling_price: this.selectedProduct.selling_price,
                    quantity: this.selectedProduct.quantity,
                    total_amount: this.selectedProduct.total_amount,
                }

                this.carts.push(this.product)
                this.selectedProduct = {
                    id: "",
                    display_name: "",
                    name: "",
                    purchase_price: "",
                    selling_price: "",
                }
                this.TotalAmount()
            } else {
                alert("Product Select First")
                document.querySelector("#product [type='search']").focus()
            }
        },

        TotalAmount() {
            this.purchase.subtotal = this.carts.reduce((acc, pre) => { return (+parseFloat(acc) + +parseFloat(pre.total_amount)).toFixed(2) }, 0)
            this.purchase.due = this.purchase.subtotal
            this.purchase.total = this.purchase.subtotal
            //vat calculate
            this.purchase.vat_amount = ((parseFloat(this.purchase.subtotal) * this.purchase.vat) / 100).toFixed(2)
            this.purchase.total = (parseFloat(this.purchase.total) + parseFloat(this.purchase.vat_amount)).toFixed(2)
            //discount calculate
            this.purchase.discount_amount = ((parseFloat(this.purchase.subtotal) * this.purchase.discount) / 100).toFixed(2)
            this.purchase.total = (parseFloat(this.purchase.total) - parseFloat(this.purchase.discount_amount)).toFixed(2)
            //transport_cost calculate
            this.purchase.total = (+parseFloat(this.purchase.total) + +this.purchase.transport_cost).toFixed(2)
            //total paid claculate
            this.purchase.due = (parseFloat(this.purchase.total) - parseFloat(this.purchase.paid)).toFixed(2)
            if(this.selectedSupplier.supplier_type == "G"){
                this.purchase.paid = this.purchase.total
                this.purchase.due = 0
            }
        },
        removeCart(item) {
            var index = this.carts.indexOf(item);
            this.carts.splice(index, 1);
            this.TotalAmount()
        },

        savePurchase(event) {
            let data = {
                purchase: this.purchase,
                carts: this.carts,
                supplier: this.selectedSupplier
            }
            axios.post("/api/save-purchase", data)
                .then(res => {
                    this.$toastr.s(res.data.msg, "Success!");
                    if (confirm("Are you sure want print")) {
                        this.$router.push({ path: '/purchase-invoice/' + res.data.invoice })
                    } else {
                        this.$router.push({ path: "/purchases-list" })
                    }
                    this.clearData()
                    this.getPurchase()
                    this.carts = [];
                })
        },

        clearData() {
            this.purchase = {
                date: moment().format("YYYY-MM-DD"),
                subtotal: 0,
                total: 0,
                paid: 0,
                vat: 0,
                vat_amount: 0,
                discount: 0,
                previous_due: 0,
                discount_amount: 0,
                transport_cost: 0,
                payment_type: 'cash',
                due: 0,
                invoice: "",
                note: "",
                added_by: "",
                account_id: "",
            }
            this.selectedSupplier = {
                id: "",
                name: "",
            }
        },

        CategoryChange() {
            if (this.selectedCategory.id == 0) {
                this.products = this.products1
                return
            }
            this.products = this.products1.filter(p => p.category_id == this.selectedCategory.id);
        },
        BrandChange() {
            if (this.selectedBrand.id == 0) {
                this.products = this.products1
                return
            }
            this.products = this.products1.filter(p => p.brand_id == this.selectedBrand.id);
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
            this.useraccess.includes("purchase.edit") ? "" : location.href = "/unauthorize"
        },
    },
    mounted() {
        document.title = "Perchase Edit Page"
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

#supplier #vs3__listbox {
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