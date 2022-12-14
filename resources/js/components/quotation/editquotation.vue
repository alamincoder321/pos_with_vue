<template>
    <div class="container-fluid px-4 mt-2">
        <div class="row">
            <div class="col-12">
                <div class="card" style="border-bottom:none;">
                    <div class="card-header">
                        <div class="row">
                            <label for="invoice" class="col-4 col-lg-1">Invoice:</label>
                            <div class="col-8 col-lg-2">
                                <input type="text" v-model="quotation.invoice" readonly class="form-control shadow-none">
                            </div>

                            <label for="category" class="col-4 col-lg-2">Category:</label>
                            <div class="col-8 col-lg-3">
                                <v-select label="name" id="category" name="category_id" :options="categories"
                                    v-model="selectedCategory" @input="CategoryChange"></v-select>
                            </div>

                            <label for="brand" class="col-4 col-lg-1">Brand:</label>
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
                        <h4>Customer && Product Information</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 col-lg-6">                                
                                <div class="row mt-2">
                                    <label for="customer_name" class="col-5 col-lg-4 d-flex align-items-center">Name:</label>
                                    <div class="col-7 col-lg-8">
                                        <input type="text" id="customer_name" name="customer_name" class="form-control shadow-none"
                                            v-model="quotation.customer_name" autocomplete="off" />
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <label for="customer_phone" class="col-5 col-lg-4 d-flex align-items-center">Phone:</label>
                                    <div class="col-7 col-lg-8">
                                        <input type="text" id="customer_phone" name="customer_phone" class="form-control shadow-none"
                                            v-model="quotation.customer_phone" autocomplete="off" />
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <label for="customer_address"
                                        class="col-5 col-lg-4 d-flex align-items-center">Address:</label>
                                    <div class="col-7 col-lg-8">
                                        <textarea id="customer_address" name="customer_address" class="form-control shadow-none"
                                            v-model="quotation.customer_address" autocomplete="off"></textarea>
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
                                    <label for="warranty"
                                        class="col-5 col-lg-4 d-flex align-items-center">Warranty:</label>
                                    <div class="col-7 col-lg-3 pe-lg-0">
                                        <input type="text" id="warranty" name="warranty"
                                            class="form-control shadow-none" v-model="selectedProduct.warranty" autocomplete="off" />
                                    </div>
                                    <label for="quantity" class="col-5 col-lg-1 d-flex align-items-center">Qty:</label>
                                    <div class="col-7 col-lg-4">
                                        <input type="number" min="0" id="quantity" name="quantity"
                                            v-model="selectedProduct.quantity" @input="cartQtyQuotationChange"
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
                                <div class="row mt-2">
                                    <div class="col-12 text-end">
                                        <button class="btn btn-primary shadow-none"
                                            @click="AddToCart">AddToCart</button>
                                    </div>
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
                                    <th class="text-center">Sale Rate</th>
                                    <th class="text-center">Quantity</th>
                                    <th class="text-center">Warranty</th>
                                    <th class="text-center">Total Amount</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(item, index) in carts" :key="index">
                                    <td class="text-center">{{ index + 1 }}</td>
                                    <td class="text-center">{{ item.name }}</td>
                                    <td class="text-center">{{ item.selling_price }}</td>
                                    <td class="text-center">{{ item.quantity }}</td>
                                    <td class="text-center">{{ item.warranty }}</td>
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
                                                <textarea name="note" id="note" v-model="quotation.note"
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
                            <form @submit.prevent="saveQuotation">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="date">Date:</label>
                                        <VueDatePicker v-model="quotation.date" :style="color" />
                                    </div>
                                    <div class="form-group">
                                        <label for="subtotal">SubTotal:</label>
                                        <input type="number" id="subtotal" name="subtotal" v-model="quotation.subtotal"
                                            class="form-control shadow-none" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label for="vat">Vat:</label>
                                        <div class="row">
                                            <div class="col-6 col-lg-7">
                                                <div class="input-group">
                                                    <input type="number" style="height:30px;" id="vat" name="vat"
                                                        @input="TotalAmount" v-model="quotation.vat"
                                                        class="form-control shadow-none"><span
                                                        style="height:30px;line-height:1;"
                                                        class="btn btn-warning">%</span>
                                                </div>
                                            </div>
                                            <div class="col-6 col-lg-5">
                                                <input type="number" id="vat_amount" name="vat_amount"
                                                    v-model="quotation.vat_amount" class="form-control shadow-none" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="discount">Discount:</label>
                                        <div class="row">
                                            <div class="col-6 col-lg-7">
                                                <div class="input-group">
                                                    <input type="number" style="height:30px;" id="discount"
                                                        @input="TotalAmount" name="discount" v-model="quotation.discount"
                                                        class="form-control shadow-none"><span
                                                        style="height:30px;line-height:1;"
                                                        class="btn btn-warning">%</span>
                                                </div>
                                            </div>
                                            <div class="col-6 col-lg-5">
                                                <input type="number" id="discount_amount" name="discount_amount"
                                                    v-model="quotation.discount_amount" class="form-control shadow-none"
                                                    readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="transport_cost">Labour Cost/Transport Cost:</label>
                                        <input type="number" id="transport_cost" name="transport_cost"
                                            @input="TotalAmount" v-model="quotation.transport_cost"
                                            class="form-control shadow-none">
                                    </div>
                                    <div class="form-group">
                                        <label for="total">Total:</label>
                                        <input type="number" id="total" name="total" v-model="quotation.total"
                                            class="form-control shadow-none" readonly>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-5">
                                            <button class="btn btn-secondary shadow-none w-100">Reset</button>
                                        </div>
                                        <div class="col-7">
                                            <button class="btn btn-success shadow-none w-100">Quotation</button>
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
            products: [],
            products1: [],
            selectedProduct: {
                id: '',
                product_code: '',
                display_name: 'Select Product',
                name: '',
                quantity: '',
                warranty: "",
                purchase_price: '',
                selling_price: 0.00,
                total_amount: ''
            },
            carts: [],
            quotation: {
                id              : "",
                date            : moment(new Date()).format("YYYY-MM-DD"),
                customer_name   : "",
                customer_phone  : "",
                customer_address: "",
                subtotal        : 0,
                total           : 0,
                vat             : 0,
                vat_amount      : 0,
                discount        : 0,
                discount_amount : 0,
                transport_cost  : 0,
                invoice         : "",
                note            : "",
                added_by        : "",
            },
            useraccess: [],
            user_id: null,
        }
    },
    created() {
        this.user_id = localStorage.getItem("user_id");
        this.quotation.added_by = this.user_id;
        this.getQuotation();
        this.getCategory();
        this.getBrand();
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
        getProduct() {
            axios.get("/api/get_product").then((res) => {
                this.products = res.data.products;
                this.products1 = res.data.products;
                this.products.unshift({ id: 0, display_name: "Select Product" })
            });
        },
        getQuotation() {
            let data = { invoice: this.$route.params.id }
            axios.post("/api/get_quotation", data).then((res) => {
                this.quotation = res.data.quotations[0]
                this.carts = res.data.quotations[0].quotationDetails
            });
        },
        onChangeProduct() {
            if (this.selectedProduct == null) {
                this.selectedProduct = {
                    id: "",
                    display_name: "",
                    name: "",
                    quantity: "",
                    warranty: "",
                    purchase_price: "",
                    selling_price: "",
                }
                return
            }
        },

        cartQtyQuotationChange() {
            this.selectedProduct.total_amount = (this.selectedProduct.quantity * this.selectedProduct.selling_price).toFixed(2)
        },

        AddToCart() {
            if (this.selectedProduct.id != "") {
                let cartInd = this.carts.findIndex(p => p.id == this.selectedProduct.id);
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
                    warranty: this.selectedProduct.warranty,
                    total_amount: this.selectedProduct.total_amount,
                }                
                this.carts.push(this.product)
                this.selectedProduct = {
                    id            : "",
                    display_name  : "",
                    name          : "",
                    purchase_price: "",
                    warranty      : "",
                    selling_price : "",
                }
                this.TotalAmount()
            } else {
                alert("Product Select First")
                document.querySelector("#product [type='search']").focus()
            }
        },

        TotalAmount() {
            this.quotation.subtotal = this.carts.reduce((acc, pre) => { return (+parseFloat(acc) + +parseFloat(pre.total_amount)).toFixed(2) }, 0)
            this.quotation.total = this.quotation.subtotal
            //vat calculate
            this.quotation.vat_amount = ((parseFloat(this.quotation.subtotal) * this.quotation.vat) / 100).toFixed(2)
            this.quotation.total = (parseFloat(this.quotation.total) + parseFloat(this.quotation.vat_amount)).toFixed(2)
            //discount calculate
            this.quotation.discount_amount = ((parseFloat(this.quotation.subtotal) * this.quotation.discount) / 100).toFixed(2)
            this.quotation.total = (parseFloat(this.quotation.total) - parseFloat(this.quotation.discount_amount)).toFixed(2)
            //transport_cost calculate
            this.quotation.total = (+parseFloat(this.quotation.total) + +this.quotation.transport_cost).toFixed(2)
        },
        removeCart(item) {
            var index = this.carts.indexOf(item);
            this.carts.splice(index, 1);
            this.TotalAmount()
        },

        saveQuotation(event) {
            let data = {
                quotation: this.quotation,
                carts: this.carts,
            }
            axios.post("/api/save_quotation", data)
                .then(res => {
                    alert(res.data.msg)
                    if (confirm("Are you sure want print")) {
                        this.$router.push({ path: '/quotaion-invoice/' + res.data.invoice })
                    } else {
                        this.$router.push({ path: "/quotations-list" })
                    }
                    this.clearData()
                    this.getQuotation()
                    this.carts = [];
                })
        },

        clearData() {
            this.quotation = {
                id              : "",
                date            : moment(new Date()).format("YYYY-MM-DD"),
                customer_name   : "",
                customer_phone  : "",
                customer_address: "",
                subtotal        : 0,
                total           : 0,
                vat             : 0,
                vat_amount      : 0,
                discount        : 0,
                discount_amount : 0,
                transport_cost  : 0,
                invoice         : "",
                note            : "",
                added_by        : "",
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
            this.useraccess.includes("quotation.edit") ? "" : location.href = "/unauthorize"
        },
    },
    mounted() {
        document.title = "Quotation Edit Page"
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