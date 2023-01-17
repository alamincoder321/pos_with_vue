<template>
    <div class="container-fluid px-3 mt-2">
   <div class="row">
      <div class="col-12 col-lg-12">
         <div class="card">
            <div class="card-header" style="background:linear-gradient(45deg, #bb3a87, #000000d1);">
               <form @submit.prevent="getSearchPurchase">
                  <div class="row">
                     <div class="col-lg-2">
                        <div class="form-group m-0">
                           <select class="form-select shadow-none" v-model="searchBy">
                              <option value="">All</option>
                              <option value="invoice"> By Invoice </option>
                           </select>
                        </div>
                     </div>
                     <div class="col-lg-3" :style="{display: searchBy == 'invoice' ? '' : 'none'}">
                        <div class="form-group m-0">
                           <v-select :options="invoices" id="invoice" v-model="selectedInvoice"
                              label="invoice"></v-select>
                        </div>
                     </div>
                     <div class="col-lg-2" :style="{display: searchBy == 'invoice' ? 'none' : ''}">
                        <div class="form-group m-0">
                           <input type="date" class="form-control shadow-none" v-model="dateFrom" />
                        </div>
                     </div>
                     <div class="col-lg-2" :style="{display: searchBy == 'invoice' ? 'none' : ''}">
                        <div class="form-group m-0">
                           <input type="date" class="form-control shadow-none" v-model="dateTo" />
                        </div>
                     </div>
                     <div class="col-lg-2">
                        <div class="form-group m-0">
                           <button type="submit" class="btn btn-info btn-sm text-white shadow-none px-3">
                           Submit
                           </button>
                        </div>
                     </div>
                  </div>
               </form>
            </div>
            <div class="card-body" style="overflow-x:auto;" :style="{ display: purchases.length > 0 ? '' : 'none' }">
               <table class="table table-bordered m-0">
                  <thead class="bg-info text-white text-center">
                     <tr>
                        <th style="width: 8%">
                           Sl
                        </th>
                        <th style="width: 10%">
                           #Invoice
                        </th>
                        <th style="width: 10%">
                           Date
                        </th>
                        <th>
                           Supplier Details
                        </th>
                        <th>
                           Amount Details
                        </th>
                        <th style="width: 12%">
                           Action
                        </th>
                     </tr>
                  </thead>
                  <tbody>
                     <template v-for="(item, index) in purchases">
                        <tr :title="item.user_name">
                           <td class="text-center">
                              {{ index + 1 }}
                           </td>
                           <td class="text-center">
                              {{ item.invoice }}
                           </td>
                           <td class="text-center">
                              {{ formatDate(item.date) }}
                           </td>
                           <td>
                              <span>Supplier Name:
                              {{ item.name }}</span><br />
                              <span>Mobile: {{ item.phone }}</span><br />
                              <span>Address:
                              {{ item.address }}</span><br />
                              <span v-if="item.supplier_type != 'G'">Previous Due:
                              {{ item.previous_due }}</span>
                              <span v-else>General Supplier</span>
                           </td>
                           <td>
                              <span>SubTotal:
                              {{ item.subtotal }}</span><br />
                              <span>Total: {{ item.total }}</span><br />
                              <span>Due: {{ item.due }}</span><br />
                              <span v-if="item.discount != 0">Discount ({{
                              item.discount
                              }}%):
                              {{ item.discount_amount }}</span><br />
                              <span v-if="item.vat != 0">Vat ({{ item.vat }}%):
                              {{ item.vat_amount }}</span><br />
                              <span v-if="item.transport_cost != 0">Transport Cost:
                              {{ item.transport_cost }}</span>
                           </td>
                           <td>
                              <div class="input-group gap-2">
                                 <router-link class="bg-common" style="padding:2px 6px;" title="Purchase Invoice" :to="{path: '/purchase-invoice/' + item.invoice}"><i
                                    class="fas fa-file text-info"></i>
                                </router-link>
                                 <router-link class="bg-common" title="purchase-edit" :to="{path:'/purchases-edit/' + item.invoice}">
                                    <i class="fa fa-edit text-primary"></i>
                                 </router-link>
                                 <button title="Purchase Delete" @click="InvoiceDelete(item.id,index) " type="button" class="shadow-none outline-none border-0">
                                    <i class="fas fa-trash text-danger"></i>
                                 </button>
                              </div>
                           </td>
                        </tr>
                     </template>
                  </tbody>
               </table>
            </div>
            <div class="card-body" :style="{ display: purchases.length > 0 ? 'none' : '' }">
               <p class="m-0 text-center">Not Found Data in Table</p>
            </div>
         </div>
      </div>
   </div>
</div>
</template>

<script>
var moment = require("moment");
export default {
    data() {
        return {
            searchBy: "",
            dateFrom: moment().format("YYYY-MM-DD"),
            dateTo: moment().format("YYYY-MM-DD"),
            invoices: [],
            selectedInvoice: null,
            purchases: [],
            useraccess: [],
            user_id: null,
        };
    },
    created() {
        this.user_id = localStorage.getItem("user_id");
        this.getSearchPurchase();
        this.getPurchases();
        this.getPermission();
        this.logOut();
    },
    methods: {
        getSearchPurchase() {
            if (this.searchBy == "invoice" && this.selectedInvoice == null) {
                alert("Select first Invoice");
                document.querySelector("#invoice [type='search']").focus();
                return;
            }

            let data = {
                invoice: this.searchBy != "" ? this.selectedInvoice.invoice : "",
                dateFrom: this.searchBy != "" ? "" : this.dateFrom,
                dateTo: this.searchBy != "" ? "" : this.dateTo,
            };

            axios.post("/api/get_purchase", data).then((res) => {
                this.purchases = res.data.purchases;
            });
        },
        getPurchases() {
            axios.post("/api/get_purchase").then((res) => {
                this.invoices = res.data.purchases;
            });
        },

        InvoiceDelete(id, sl) {
            if (confirm("Are you sure want to delete")) {
                axios.get("/api/delete_purchase/" + id).then((res) => {
                    alert(res.data);
                    var index = this.purchases.indexOf(sl);
                    this.purchases.splice(index, 1);
                });
            }
        },

        formatDate(date) {
            return moment(date).format("DD-MM-YYYY");
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
        searchBy() {
            if (this.searchBy == "") {
                this.selectedInvoice = null;
            }
        },
        useraccess() {
            this.useraccess.includes("purchase.index")
                ? ""
                : (location.href = "/unauthorize");
        },
    },

    mounted() {
        document.title = "Purchase List Page";
    },
};
</script>

<style>
.searchBtn {
    border: none;
    background: green;
    color: white;
    padding: 3px 12px;
    font-size: 15px;
    border-radius: 0.2rem;
}
</style>
