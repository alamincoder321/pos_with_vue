<template>
    <div class="container-fluid mt-2">
        <div class="row">
            <div class="col-12 col-lg-12">
                <div class="card">
                    <div class="card-header position-relative"
                        style="background:linear-gradient(45deg, #bb3a87, #000000d1);">
                        <div class="row">
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <select class="form-control shadow-none" v-model="changeVal"
                                        @change="onChangeValue">
                                        <option value="">All</option>
                                        <option value="current">Current Stock</option>
                                        <option value="brand">Brand Wise</option>
                                        <option value="category">Category Wise</option>
                                        <option value="product">Product Wise</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3" v-if="changeVal == 'brand'">
                                <div class="form-group">
                                    <v-select style="width:100% !important;" :options="brands" v-model="selectedBrand"
                                        label="name"></v-select>
                                </div>
                            </div>
                            <div class="col-lg-3" v-if="changeVal == 'category'">
                                <div class="form-group">
                                    <v-select style="width:100% !important;" :options="categories"
                                        v-model="selectedCategory" label="name"></v-select>
                                </div>
                            </div>
                            <div class="col-lg-3" v-if="changeVal == 'product'">
                                <div class="form-group">
                                    <v-select style="width:100% !important;" :options="products"
                                        v-model="selectedProduct" label="display_name"></v-select>
                                </div>
                            </div>
                            <div class="col-lg-1">
                                <button type="button" @click="getStock"
                                    class="btn btn-info btn-sm text-white shadow-none px-3">
                                    Submit
                                </button>
                            </div>
                        </div>

                        <button v-if="stocks.length > 0" class="btn btn-sm shadow-none text-white btn-print"
                            @click="print"><i class="bi bi-printer"></i> Print</button>
                    </div>
                    <div class="card-body" v-if="stocks.length > 0">
                        <table id="stocks" class="table table-sm table-hover table-bordered">
                            <thead style="background: #897800;color: white;">
                                <tr class="text-center" style="font-size: 12px;">
                                    <th>Sl</th>
                                    <th>Product Code</th>
                                    <th>Product Name</th>
                                    <th>Stock</th>
                                    <th>Price</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(item, index) in stocks" :key="index">
                                    <td>{{ index + 1 }}</td>
                                    <td>{{ item.product_code }}</td>
                                    <td>{{ item.name }}</td>
                                    <td align="center">{{ item.stock }} {{ item.unit_name }}</td>
                                    <td align="center">{{ item.selling_price }}</td>
                                    <td align="right">{{ (item.selling_price * item.stock).toFixed(2) }}</td>
                                </tr>
                                <tr :style="{ display: stocks.length == 0 ? 'none' : '' }">
                                    <th colspan="3" style="text-align:center;">Total:</th>
                                    <th style="text-align:center;">{{
                                        stocks.reduce((acc, pre) => {
                                            return acc +
                                                +pre.stock
                                        }, 0)
                                    }}</th>
                                    <th colspan="2" style="text-align:right;">{{ (stocks.reduce((acc, pre) => {
                                        return acc +
                                            +pre.stock * pre.selling_price
                                    }, 0)).toFixed(2) }} </th>
                                </tr>
                                <tr :style="{ display: stocks.length == 0 ? '' : 'none' }">
                                    <td colspan="6" class="text-center">Not Found Data</td>
                                </tr>
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
            changeVal: "",
            brands: [],
            selectedBrand: null,
            categories: [],
            selectedCategory: null,
            products: [],
            selectedProduct: null,
            stocks: [],
            company: [],
            useraccess: [],
            user_id: null,
        }
    },
    created() {
        this.user_id = localStorage.getItem("user_id");
        this.getPermission();
        this.getCompany()
        this.logOut();
    },
    methods: {
        getCompany() {
            axios.get("/api/get-company-profile").then((res) => {
                this.company = res.data;
            });
        },
        getBrand() {
            axios.get(location.origin + "/api/get-brand")
                .then(res => {
                    this.brands = res.data
                })
        },
        getCategory() {
            axios.get(location.origin + "/api/get-category")
                .then(res => {
                    this.categories = res.data
                })
        },
        getProduct() {
            axios.get(location.origin + "/api/get-product")
                .then(res => {
                    this.products = res.data.products
                })
        },
        onChangeValue() {
            this.stocks = [];
            this.selectedBrand = null
            this.selectedCategory = null
            this.selectedProduct = null

            if (this.changeVal == "brand") {
                this.getBrand()
            } else if (this.changeVal == "category") {
                this.getCategory()
            } else if (this.changeVal == "current") {
                this.getProduct()
            } else {
                this.getProduct()
            }
        },
        getStock() {
            if (this.changeVal == "brand" && this.selectedBrand == null) {
                alert("Please select Brand")
                return
            }
            if (this.changeVal == "category" && this.selectedCategory == null) {
                alert("Please select Category")
                return
            }
            let data = {
                id: this.selectedProduct != null ? this.selectedProduct.id : "",
            }

            axios.post(location.origin + "/api/get-product-stock", data)
                .then(res => {
                    if (this.changeVal == "category") {
                        this.stocks = res.data.filter(p => p.category_id == this.selectedCategory.id)
                    } else if (this.changeVal == "brand") {
                        this.stocks = res.data.filter(p => p.brand_id == this.selectedBrand.id)
                    } else if (this.changeVal == "current") {
                        this.stocks = res.data.filter(p => p.stock > 0);
                    } else {
                        this.stocks = res.data
                    }
                })
        },

        async print() {
            var myWindow = window.open('', '', `width=${window.screen.width},height=${window.screen.height}`);
            myWindow.document.write(`
                    <html>
                        <head>
                            <title>Stock Report</title>
                            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" />
                            <style>
                                * {
                                    font-family: unset;
                                }    
                                img {
                                    width: 100%;
                                    height: 70px;
                                }    
                                h2 {
                                    color: #939393 !important;
                                }    
                                @media print {
                                    *{
                                        margin:0 auto;
                                    }
                                    .table thead {
                                        background: #897800 !important;
                                        color: white !important;
                                    }
                                    #stocks{
                                        page-break-inside:avoid;
                                        page-break-after:always;
                                    }
                                }
                            </style>
                        </head>
                        <body>
                            <div class='container'>
                                <div class="row">
                                    <div class="col-12 text-center" style="border-bottom:1.5px dashed gray;">
                                        <h2 class="text-uppercase m-0">${this.company.name}</h2>
                                        <p class="m-0 text-uppercase">${this.company.owner_name}</p>
                                        <p class="m-0 text-uppercase">${this.company.phone}</p>
                                        <address style="margin:0;">${this.company.address}</address>
                                    </div>
                                </div>
                                <div class="row">
                                    <h2 style="margin:0;text-align:center">Stock Report</h2>
                                    <table class="table table-sm table-bordered m-0">
                                        ${document.querySelector("#stocks").innerHTML}
                                    </table>
                                </div>
                                <div style='width:100%;position:fixed;bottom:0;left:0;text-align:center;padding-top:8px;font-style:italic;'>Print Date: ${moment(new Date()).format("DD-MM-YYYY")}<div>
                            </div>
                        </body>
                    </html>
                `);
            myWindow.focus();
            await new Promise(resolve => setTimeout(resolve, 500));
            myWindow.print();
            myWindow.close();
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
            this.useraccess.includes("dashboard") ? "" : location.href = "/unauthorize"
        }
    },
    mounted() {
        document.title = "Product Stock"
    },
};
</script>
