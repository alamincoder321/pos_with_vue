<template>
    <div class="container-fluid px-3 mt-2">
       <div class="row">
          <div class="col-12 col-lg-12">
             <div class="card">
                <div class="card-header" style="background:linear-gradient(45deg, #bb3a87, #000000d1);">
                   <form @submit.prevent="getLedger">
                      <div class="row">
                         <div class="col-lg-3">
                            <div class="form-group m-0">
                               <v-select :options="products" id="product" v-model="selectedProduct"
                                  label="display_name"></v-select>
                            </div>
                         </div>
                         <div class="col-lg-2">
                            <div class="form-group m-0">
                               <input type="date" class="form-control shadow-none" v-model="dateFrom" />
                            </div>
                         </div>
                         <div class="col-lg-2">
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
                <div class="card-body" style="overflow-x:auto;" v-if="ledgers.length > 0">

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
 var moment = require("moment");
 export default {
    data() {
       return {
          dateFrom: moment().format("YYYY-MM-DD"),
          dateTo: moment().format("YYYY-MM-DD"),
          products: [],
          selectedProduct: null,
          ledgers: [],
          useraccess: [],
          user_id: null,
       };
    },
    created() {
       this.user_id = localStorage.getItem("user_id");
       this.getProduct();
       this.getLedger();
       this.getPermission();
       this.logOut();
    },
    methods: {
        getLedger() {
          if (this.selectedProduct == null) {
             alert("Select first Product");
             document.querySelector("#product [type='search']").focus();
             return;
          }
 
          let data = {
             id: this.selectedProduct != null ? this.selectedProduct.id : "",
             dateFrom: this.dateFrom,
             dateTo: this.dateTo,
          };
 
          axios.post("/api/get_ledger", data).then((res) => {
             this.purchases = res.data.purchases;
          });
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
 