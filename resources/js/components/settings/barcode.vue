<template>
    <div class="container-fluid px-4">
        <div class="row">
            <div class="col-12 mt-3">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Barcode BarcodeGenerate</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 col-lg-6">
                                <div class="row mt-2">
                                    <label for="product_code" class="col-5 col-lg-4 d-flex align-items-center">Product
                                        Code:</label>
                                    <div class="col-7 col-lg-8">
                                        <input type="text" id="product_code" class="form-control shadow-none"
                                            v-model="product.product_code" disabled autocomplete="off" />
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <label for="quantity" class="col-5 col-lg-4 d-flex align-items-center">Product
                                        Quantity:</label>
                                    <div class="col-7 col-lg-8">
                                        <input type="number" step="1" min="0" id="quantity"
                                            class="form-control shadow-none" v-model="quantity" autocomplete="off" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-lg-6">
                                <div class="row mt-2">
                                    <label for="name" class="col-5 col-lg-4 d-flex align-items-center">Product
                                        Name:</label>
                                    <div class="col-7 col-lg-8">
                                        <input type="text" id="name" class="form-control shadow-none"
                                            v-model="product.name" disabled autocomplete="off" />
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <label for="article" class="col-5 col-lg-4 d-flex align-items-center">Product
                                        Article:</label>
                                    <div class="col-7 col-lg-8">
                                        <input type="text" id="article" class="form-control shadow-none"
                                            v-model="article" autocomplete="off" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group text-center mt-3">
                            <button @click="print" type="button" class="btn btn-sm shadow-none text-white btn-invoice"><i
                                    class="bi bi-printer"></i> Print</button>
                            <button @click="BarcodeGenerate" type="button"
                                class="btn btn-sm btn-outline-success px-3 shadow-none">BarcodeGenerate</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row QR" v-if="products.length > 0">
            <template v-for="(item, index) in products">
                <div style="width:200px;">
                    <barcode v-bind:value="product.product_code"></barcode>
                </div>
            </template>
        </div>
    </div>
</template>

<script>
import VueBarcode from 'vue-barcode';
export default {
    components: {
        'barcode': VueBarcode
    },
    data() {
        return {
            quantity: 0,
            article: "",
            product: {},
            products: [],
        };
    },
    created() {
        this.getProduct();
    },
    methods: {
        getProduct() {
            axios.get("/api/product-fetch/" + this.$route.params.id)
                .then(res => {
                    this.product = res.data
                })
        },
        BarcodeGenerate() {
            this.products = [];
            if (this.quantity == 0) {
                alert("Quantity must be fillup")
                return;
            }
            let i = 0;
            while (i < this.quantity) {
                this.products.push(this.product);
                i++;
            }

        },
        async print(){
            let printWindow = window.open("", "", `width=${window.screen.width}, height=${window.screen.height}`)
            printWindow.document.write(`
                    <html>
                        <head>
                            <title>Stock Report</title>
                            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" />
                            <style>    
                                @media print {
                                    *{
                                        margin:0 auto;
                                    }
                                    @page{size:auto;}
                                }
                            </style>
                        </head>
                        <body>
                            <table style="width:100%;">
                                <tr>
                                    <td>
                                        ${document.querySelector(".QR").innerHTML}
                                    </td>
                                </tr>
                            </table>
                        </body>
                    </html>
                `);
            printWindow.focus();
            await new Promise(resolve => setTimeout(resolve, 500));
            printWindow.print();
            printWindow.close();
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
            this.useraccess.includes("brand.index") ? "" : location.href = "/unauthorize"
        }
    },

    mounted() {
        document.title = "Brand Page";
    },
};
</script>
