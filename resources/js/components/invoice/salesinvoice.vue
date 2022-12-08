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
                        Sales Invoice</h6>
                </div>
                <div class="col-6 mb-3" style="line-height: 20px;">
                    <span style="font-weight: 500;">Customer Id: </span>{{ sales[0].code }}<br>
                    <span style="font-weight: 500;">Customer Name: </span>{{ sales[0].name }}<br>
                    <span style="font-weight: 500;">Customer Address: </span>{{ sales[0].address }}<br>
                    <span style="font-weight: 500;">Customer Mobile: </span>{{ sales[0].phone }}
                </div>
                <div class="col-6 mb-3">
                    <div class="text-end">
                        <span style="font-weight: 500;">Sales By:</span>{{ sales[0].user_name }}<br>
                        <span style="font-weight: 500;">Invoice:</span>#{{ sales[0].invoice }}<br>
                        <span style="font-weight: 500;">Sales Date:</span> {{ formatDate(sales[0].date) }}
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
                            <tr v-for="(item, index) in sales[0].saleDetails" :key="index">
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
                            <td style="text-align: right;">{{ sales[0].previous_due }}</td>
                        </tr>
                        <tr>
                            <td style="font-weight: 600;">Current Due</td>
                            <td>:</td>
                            <td style="text-align: right;">{{ sales[0].due }}</td>
                        </tr>
                        <tr style="border-top: 1px dashed gray;">
                            <td style="font-weight: 600;">Total Due</td>
                            <td>:</td>
                            <td style="text-align: right;">
                                {{ (parseFloat(sales[0].previous_due) + parseFloat(sales[0].due)).toFixed(2) }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-5">
                    <table style="line-height: 23px;width: 100%;">
                        <tr>
                            <td style="font-weight: 600;width: 130px;">SubTotal</td>
                            <td>:</td>
                            <td style="text-align: right;">{{ sales[0].subtotal }}</td>
                        </tr>
                        <tr>
                            <td style="font-weight: 600;width: 130px;">VAT</td>
                            <td>:</td>
                            <td style="text-align: right;">{{ sales[0].vat_amount }}</td>
                        </tr>
                        <tr>
                            <td style="font-weight: 600;width: 130px;">Discount</td>
                            <td>:</td>
                            <td style="text-align: right;">{{ sales[0].discount_amount }}</td>
                        </tr>
                        <tr>
                            <td style="font-weight: 600;width: 130px;">Transport Cost</td>
                            <td>:</td>
                            <td style="text-align: right;">{{ sales[0].transport_cost }}</td>
                        </tr>
                        <tr style="border-top: 1px dashed gray;">
                            <td style="font-weight: 600;width: 130px;">Total</td>
                            <td>:</td>
                            <td style="text-align: right;">{{ sales[0].total }}</td>
                        </tr>
                        <tr>
                            <td style="font-weight: 600;width: 130px;">Paid</td>
                            <td>:</td>
                            <td style="text-align: right;">{{ sales[0].paid }}</td>
                        </tr>
                        <tr style="border-top: 1px dashed gray;">
                            <td style="font-weight: 600;width: 130px;">Due</td>
                            <td>:</td>
                            <td style="text-align: right;">{{ sales[0].due }}</td>
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
            sales: [],
        }
    },

    created() {
        this.getSales()
        this.getCompany()
    },

    mounted() {
        document.title = "Sales Invoice"
    },

    methods: {
        getCompany() {
            axios.get("/api/get_company_profile").then((res) => {
                this.company = res.data;
            });
        },
        getSales() {
            axios.post("/api/get_sale", { invoice: this.$route.params.id }).then((res) => {
                console.log(res.data);
                this.sales = Array.from(res.data.sales)
            });
        },
        formatDate(date) {
            return moment(date).format("DD/MM/YYYY");
        },

        async PrintInvoice() {
            var myWindow = window.open('', 'PRINT');
            myWindow.document.write(`
                <html>
                    <head>
                        <title>Invoice</title>
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
                        </div>
                    </body>
                </html>
			`);
            myWindow.focus();
            await new Promise(resolve => setTimeout(resolve, 1000));
            myWindow.print();
            myWindow.close();
        },
    },

}

</script>
