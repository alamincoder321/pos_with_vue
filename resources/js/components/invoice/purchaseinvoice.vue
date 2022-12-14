<style>
* {
    font-family: unset;
}

.table thead tr th {
    font-size: 12px;
}

.table tbody tr td {
    font-size: 12px;
}

img {
    width: 100%;
    height: 70px;
}

h2 {
    color: #939393 !important;
}

@media print {
    img {
        width: 100% !important;
        height: 70px !important;
    }

    h2 {
        color: #939393 !important;
    }
}
</style>

<template>
    <div style="width:80%;margin:auto;">
        <div class="container text-end">
            <button class="btn btn-warning btn-sm text-white shadow-none px-4 my-3" @click="PrintInvoice">Print</button>
        </div>
        <div class="container" id="invoice">
            <div class="row">
                <div class="col-12 mb-3">
                    <h6 class="text-center m-0"
                        style="border-top: 1px dashed gray;border-bottom: 1px dashed gray;text-transform: uppercase;padding: 8px;">
                        Purchases Invoice</h6>
                </div>
                <div class="col-6 mb-3" style="line-height: 20px;">
                    <span style="font-weight: 500;">Supplier Id: </span>{{ purchases[0].code?purchases[0].code:"General Supplier" }}<br>
                    <span style="font-weight: 500;">Supplier Name: </span>{{ purchases[0].name }}<br>
                    <span style="font-weight: 500;">Supplier Address: </span>{{ purchases[0].address }}<br>
                    <span style="font-weight: 500;">Supplier Mobile: </span>{{ purchases[0].phone }}
                </div>
                <div class="col-6 mb-3">
                    <div class="text-end">
                        <span style="font-weight: 500;">Purchases By:</span>{{ purchases[0].user_name }}<br>
                        <span style="font-weight: 500;">Invoice:</span>#{{ purchases[0].invoice }}<br>
                        <span style="font-weight: 500;">Purchases Date:</span> {{ formatDate(purchases[0].date) }}
                    </div>
                </div>
                <!-- product details -->
                <div class="col-12 mb-3">
                    <table class="table table-bordered table-sm">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-center" style="width: 5%;">Sl</th>
                                <th class="text-uppercase text-center" style="width: 50%;">Description</th>
                                <th class="text-uppercase text-center" style="width: 10%;">Qty</th>
                                <th class="text-uppercase text-center" style="width: 10%;">Price</th>
                                <th class="text-uppercase text-center" style="width: 10%;">Warranty</th>
                                <th class="text-uppercase text-center" style="width: 15%;">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(item, index) in purchases[0].purchaseDetails" :key="index">
                                <td class="text-center">{{ index + 1 }}</td>
                                <td>{{ item.name }}</td>
                                <td class="text-center">{{ item.quantity }} {{ item.unit_name }}</td>
                                <td class="text-center">{{ item.selling_price }}</td>
                                <td class="text-center">-</td>
                                <td class="text-center">{{ item.total_amount }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="col-7">
                    <table style="line-height: 22px;width: 275px;">
                        <tr>
                            <td style="font-weight: 600;">Previous Due</td>
                            <td>:</td>
                            <td style="text-align: right;">{{ purchases[0].previous_due }}</td>
                        </tr>
                        <tr>
                            <td style="font-weight: 600;">Current Due</td>
                            <td>:</td>
                            <td style="text-align: right;">{{ purchases[0].due }}</td>
                        </tr>
                        <tr style="border-top: 1px dashed gray;">
                            <td style="font-weight: 600;">Total Due</td>
                            <td>:</td>
                            <td style="text-align: right;">
                                {{ (parseFloat(purchases[0].previous_due) + parseFloat(purchases[0].due)).toFixed(2) }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-5">
                    <table style="line-height: 23px;width: 100%;">
                        <tr>
                            <td style="font-weight: 600;width: 130px;">SubTotal</td>
                            <td>:</td>
                            <td style="text-align: right;">{{ purchases[0].subtotal }}</td>
                        </tr>
                        <tr>
                            <td style="font-weight: 600;width: 130px;">VAT {{ purchases[0].vat != 0?"("+purchases[0].vat+"%)":'' }}</td>
                            <td>:</td>
                            <td style="text-align: right;">{{ purchases[0].vat_amount }}</td>
                        </tr>
                        <tr>
                            <td style="font-weight: 600;width: 130px;">Discount {{ purchases[0].discount != 0?"("+purchases[0].discount+"%)":'' }}</td>
                            <td>:</td>
                            <td style="text-align: right;">{{ purchases[0].discount_amount }}</td>
                        </tr>
                        <tr v-if="parseFloat(purchases[0].transport_cost) > 0">
                            <td style="font-weight: 600;width: 130px;">Transport Cost</td>
                            <td>:</td>
                            <td style="text-align: right;">{{ purchases[0].transport_cost }}</td>
                        </tr>
                        <tr style="border-top: 1px dashed gray;">
                            <td style="font-weight: 600;width: 130px;">Total</td>
                            <td>:</td>
                            <td style="text-align: right;">{{ purchases[0].total }}</td>
                        </tr>
                        <tr>
                            <td style="font-weight: 600;width: 130px;">Paid</td>
                            <td>:</td>
                            <td style="text-align: right;">{{ purchases[0].paid }}</td>
                        </tr>
                        <tr style="border-top: 1px dashed gray;">
                            <td style="font-weight: 600;width: 130px;">Due</td>
                            <td>:</td>
                            <td style="text-align: right;">{{ purchases[0].due }}</td>
                        </tr>
                    </table>
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
            company: [],
            purchases: "",
        }
    },

    created() {
        this.getPurchases()
        this.getCompany()
    },

    mounted() {
        document.title = "Purchases Invoice"
    },

    methods: {
        getCompany() {
            axios.get("/api/get_company_profile").then((res) => {
                this.company = res.data;
            });
        },
        getPurchases() {
            axios.post("/api/get_purchase", { invoice: this.$route.params.id }).then((res) => {
                this.purchases = res.data.purchases
            });
        },
        formatDate(date) {
            return moment(date).format("DD/MM/YYYY");
        },

        async PrintInvoice() {
            var myWindow = window.open('', 'PRINT');
            if(this.company.paper_type == '3'){
                myWindow.document.write(`
                    <html>
                        <head>
                            <title>Purchases Invoice</title>
                            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" />
                            <style>
                                * {
                                    font-family: unset;
                                }
    
                                .table thead tr th {
                                    font-size: 12px;
                                }
    
                                .table tbody tr td {
                                    font-size: 12px;
                                }
    
                                img {
                                    width: 100%;
                                    height: 70px;
                                }
    
                                h2 {
                                    color: #939393 !important;
                                }
    
                                @media print {
                                    @page{size: A5 portrait !important;}
                                    *{
                                        font-size:12px !important;
                                    }
                                    img {
                                        width: 100% !important;
                                        height: 70px !important;
                                    }
    
                                    h2 {
                                        color: #939393 !important;
                                    }
                                }
                            </style>
                        </head>
                        <body>
                            <div class='container'>
                                <div class="row mb-2">
                                    <div class="col-2">
                                        <img src="${this.company.company_logo ? location.origin + "/" + this.company.company_logo : location.origin + '/no-image.jpg'}">
                                    </div>
                                    <div class="col-10 text-center">
                                        <h2 class="text-uppercase m-0">${this.company.name}</h2>
                                        <p class="m-0 text-uppercase">${this.company.owner_name}</p>
                                        <p class="m-0 text-uppercase">${this.company.phone}</p>
                                        <address>${this.company.address}</address>
                                    </div>
                                </div>    
                                ${document.querySelector("#invoice").innerHTML}
                                <div style='width: 100%;display: flex;justify-content: space-between;position: fixed;bottom: 0;left: 0;padding-top:8px;'>
                                    <div class='text-left'>
                                        <span style='text-decoration:overline;'>Received By</span>
                                    </div>
                                    <div class='text-end'>
                                        <span style='text-decoration:overline;'>Authorized Signature</span>
                                    </div>
                                </div>
                                <div style='width:100%;position:fixed;bottom:0;left:0;text-align:center;padding-top:8px;font-style:italic;'>Print Date: ${moment(new Date()).format("DD-MM-YYYY")}<div>
                            </div>
                        </body>
                    </html>
                `);
                
            }else{
                myWindow.document.write(`
                    <html>
                        <head>
                            <title>Purchases Invoice</title>
                            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" />
                            <style>
                                * {
                                    font-family: unset;
                                }
    
                                .table thead tr th {
                                    font-size: 12px;
                                }
    
                                .table tbody tr td {
                                    font-size: 12px;
                                }
    
                                img {
                                    width: 100%;
                                    height: 70px;
                                }
    
                                h2 {
                                    color: #939393 !important;
                                }
    
                                @media print {
                                    img {
                                        width: 100% !important;
                                        height: 70px !important;
                                    }
    
                                    h2 {
                                        color: #939393 !important;
                                    }
                                }
                            </style>
                        </head>
                        <body>
                            <div class='container'>
                                <div class="row mb-2">
                                    <div class="col-2">
                                        <img src="${this.company.company_logo ? location.origin + "/" + this.company.company_logo : location.origin + '/no-image.jpg'}">
                                    </div>
                                    <div class="col-10 text-center">
                                        <h2 class="text-uppercase m-0">${this.company.name}</h2>
                                        <p class="m-0 text-uppercase">${this.company.owner_name}</p>
                                        <p class="m-0 text-uppercase">${this.company.phone}</p>
                                        <address>${this.company.address}</address>
                                    </div>
                                </div>    
                                ${document.querySelector("#invoice").innerHTML}
                                <div style='width: 100%;display: flex;justify-content: space-between;position: fixed;bottom: 0;left: 0;padding-top:8px;'>
                                    <div class='text-left'>
                                        <span style='text-decoration:overline;'>Received By</span>
                                    </div>
                                    <div class='text-end'>
                                        <span style='text-decoration:overline;'>Authorized Signature</span>
                                    </div>
                                </div>
                                <div style='width:100%;position:fixed;bottom:0;left:0;text-align:center;padding-top:8px;font-style:italic;'>Print Date: ${moment(new Date()).format("DD-MM-YYYY")}<div>
                            </div>
                        </body>
                    </html>
                `);
            }
            myWindow.focus();
            await new Promise(resolve => setTimeout(resolve, 1000));
            myWindow.print();
            myWindow.close();
        },
    },

}

</script>
