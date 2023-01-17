<template>
    <div class="container-fluid px-4">
        <div class="row">
            <div class="col-12 mt-3">
                <div class="card" :style="{display: useraccess.includes('supplier.store')?'':'none'}">
                    <div class="card-header">
                        <h4 class="card-title">Supplier Create</h4>
                    </div>
                    <form @submit.prevent="saveSupplier">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 col-lg-5">
                                    <div class="row mt-2">
                                        <label for="supplier_code"
                                            class="col-5 col-lg-4 d-flex align-items-center">Code:</label>
                                        <div class="col-7 col-lg-8">
                                            <input type="text" id="supplier_code" name="supplier_code" readonly
                                                class="form-control shadow-none" v-model="supplier.supplier_code"
                                                autocomplete="off" />
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <label for="name" class="col-5 col-lg-4 d-flex align-items-center">Name:</label>
                                        <div class="col-7 col-lg-8">
                                            <input type="text" id="name" name="name" class="form-control shadow-none"
                                                v-model="supplier.name" autocomplete="off" />
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <label for="owner_name" class="col-5 col-lg-4 d-flex align-items-center">Owner
                                            Name:</label>
                                        <div class="col-7 col-lg-8">
                                            <input type="text" id="owner_name" name="owner_name"
                                                class="form-control shadow-none" v-model="supplier.owner_name"
                                                autocomplete="off" />
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <label for="city_id" class="col-5 col-lg-4 d-flex align-items-center">City
                                            Name:</label>
                                        <div class="col-7 col-lg-8">
                                            <div class="input-group">
                                                <v-select label="name" name="city_id" :options="cities"
                                                    v-model="selectedCity"></v-select>
                                                <a href="/cities" target="_blank" class="btn btn-success shadow-none"
                                                    style="padding: 4px 8px;width: 13%;"><i class="fas fa-plus"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-lg-5">
                                    <div class="row mt-2">
                                        <label for="phone"
                                            class="col-5 col-lg-4 d-flex align-items-center">Phone:</label>
                                        <div class="col-7 col-lg-8">
                                            <input type="text" id="phone" name="phone" class="form-control shadow-none"
                                                v-model="supplier.phone" autocomplete="off" />
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <label for="address"
                                            class="col-5 col-lg-4 d-flex align-items-center">Address</label>
                                        <div class="col-7 col-lg-8">
                                            <textarea id="address" name="address" class="form-control shadow-none"
                                                v-model="supplier.address" autocomplete="off"></textarea>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <label for="previous_due"
                                            class="col-5 col-lg-4 d-flex align-items-center">Previous_due:</label>
                                        <div class="col-7 col-lg-8">
                                            <input type="number" id="previous_due" name="previous_due"
                                                class="form-control shadow-none" v-model="supplier.previous_due"
                                                autocomplete="off" />
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <label for="supplier_type"
                                            class="col-5 col-lg-4 d-flex align-items-center">Supplier Type:</label>
                                        <div class="col-7 col-lg-8">
                                            <input type="radio" id="supplier_type" name="supplier_type"
                                                v-model="supplier.supplier_type" value="retail" /> <label
                                                for="supplier_type">Retail</label>
                                            <input type="radio" id="supplier_type1" name="supplier_type"
                                                v-model="supplier.supplier_type" value="wholesale" /> <label
                                                for="supplier_type1">WholeSale</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-lg-2 d-flex justify-content-center align-items-center">
                                    <div class="form-group ImageBackground">
                                        <img :src="imageSrc" class="imageShow" />
                                        <label for="image">Image</label>
                                        <input type="file" id="image" class="form-control shadow-none"
                                            @change="imageUrl" />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group text-center mt-3">
                                <button type="button" class="btn btn-outline-secondary btn-sm shadow-none"
                                    @click="clearData">
                                    Reset
                                </button>
                                <button :style="{display: useraccess.includes('supplier.store')?'':'none'}" type="submit" class="btn btn-sm btn-outline-success shadow-none">
                                    Save Supplier
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <hr class="my-2" />
            <div class="col-12 col-lg-12" style="overflow-x:auto;">
                <vue-good-table :columns="columns" :rows="suppliers" :fixed-header="true" :pagination-options="{
                    enabled: true,
                    perPage: 15,
                }" :search-options="{
    enabled: true,
}" :line-numbers="true" styleClass="vgt-table" max-height="550px">
                    <template slot="table-row" slot-scope="props">
                        <span v-if="props.column.field == 'before'">
                            <button :style="{display: useraccess.includes('supplier.edit')?'':'none'}" class="btn btn-sm btn-outline-primary shadow-none" @click="editRow(props.row)">
                                Edit
                            </button>
                            <button :style="{display: useraccess.includes('supplier.delete')?'':'none'}" class="btn btn-sm btn-outline-danger shadow-none" @click="deleteRow(props.row.id)">
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
export default {
    data() {
        return {
            columns: [
                {
                    label: "Code",
                    field: "supplier_code",
                },
                {
                    label: "Supplier Name",
                    field: "name",
                },
                {
                    label: "Phone",
                    field: "phone",
                },
                {
                    label: "Address",
                    field: "address",
                    type: "text",
                },
                {
                    label: "Due",
                    field: "previous_due",
                    type: "number",
                },
                {
                    label: "Action",
                    field: "before",
                },
            ],
            suppliers: [],
            cities: [],
            supplier: {
                id: "",
                supplier_code: "",
                name: "",
                phone: "",
                owner_name: "",
                city_id: 1,
                address: "",
                previous_due: 0.00,
                supplier_type: "retail",
                image: "",
            },
            selectedCity: {
                id: "",
                name: "Select City"
            },
            useraccess: [],
            user_id: null,
            imageSrc: location.origin + "/no-image.jpg",
        };
    },
    created() {
        this.user_id = localStorage.getItem("user_id");
        this.getSupplier();
        this.getCity();
        this.getPermission();
        this.logOut();
    },
    methods: {
        getCity() {
            axios.get("/api/get_city").then((res) => {
                this.cities = res.data;
                this.cities.unshift({id:0, name:"Select City"})
            });
        },
        getSupplier() {
            axios.get("/api/get_supplier").then((res) => {
                this.suppliers = res.data.suppliers.filter(s => s.supplier_type != "G");
                this.supplier.supplier_code = res.data.gen_code;
            });
        },

        saveSupplier(event) {
            if (this.supplier.name == "") {
                alert("Name Field is Empty");
                return;
            }
            if (this.supplier.owner_name == "") {
                alert("Owner Name Field is Empty");
                return;
            }
            if (this.supplier.phone == "") {
                alert("Phone Field is Empty");
                return;
            }
            if (this.supplier.city_id == "") {
                alert("City Field is Empty");
                return;
            }

            let formdata = new FormData(event.target)
            formdata.append("image", this.supplier.image)
            formdata.append("id", this.supplier.id)
            formdata.append("city_id", this.selectedCity.id)
            axios
                .post(location.origin + "/api/save_supplier", formdata)
                .then((res) => {
                    alert(res.data);
                    this.clearData();
                    this.getSupplier();
                });
        },

        editRow(val) {
            this.supplier = {
                id: val.id,
                supplier_code: val.supplier_code,
                name: val.name,
                owner_name: val.owner_name,
                phone: val.phone,
                address: val.address,
                previous_due: val.previous_due,
                supplier_type: val.supplier_type,
            };
            this.selectedCity = {
                id: val.city_id,
                name: val.city_name
            }

            this.imageSrc = val.image?location.origin+"/"+val.image:location.origin+"/no-image.jpg"
        },

        deleteRow(id) {
            if (confirm("Are you sure")) {
                axios.get("/api/delete_supplier/" + id).then((res) => {
                    alert(res.data);
                    this.getSupplier();
                });
            }
        },

        imageUrl(event) {
            if (event.target.files[0]) {
                let img = new Image()
                img.src = window.URL.createObjectURL(event.target.files[0]);
                img.onload = () => {
                    if (img.width === 200 && img.height === 200) {
                        this.imageSrc = window.URL.createObjectURL(event.target.files[0]);
                        this.supplier.image = event.target.files[0];
                    } else {
                        alert(`This image ${img.width} X ${img.width} but require image 200 X 200`);
                    }
                }
            }
        },

        clearData() {
            this.supplier = {
                id: "",
                name: "",
                phone: "",
                owner_name: "",
                address: "",
                previous_due: 0.00,
                supplier_type: "retail",
                image: "",
            };
            this.selectedCity = {
                id: "",
                name: ""
            }
            this.getSupplier()
            this.imageSrc = location.origin + "/no-image.jpg"
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
        useraccess(){
            this.useraccess.includes("supplier.index")?"":location.href = "/unauthorize"
        }
    },
    mounted() {
        document.title = "Supplier Page";
    },
};
</script>

<style>
.v-select {
    width: 87% !important;
}

@media screen and (min-device-width: 360px) and (max-device-width: 768px) {
    .v-select {
        width: 81% !important;
    }
}
</style>
