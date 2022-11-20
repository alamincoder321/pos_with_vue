<template>
    <div class="container-fluid px-4">
        <div class="row">
            <div class="col-12 mt-3">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Customer Create</h4>
                    </div>
                    <form @submit.prevent="saveCustomer">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 col-lg-5">
                                    <div class="row mt-2">
                                        <label for="customer_code"
                                            class="col-5 col-lg-4 d-flex align-items-center">Code:</label>
                                        <div class="col-7 col-lg-8">
                                            <input type="text" id="customer_code" name="customer_code" readonly
                                                class="form-control shadow-none" v-model="customer_code"
                                                autocomplete="off" />
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <label for="name" class="col-5 col-lg-4 d-flex align-items-center">Name:</label>
                                        <div class="col-7 col-lg-8">
                                            <input type="text" id="name" name="name" class="form-control shadow-none"
                                                v-model="customer.name" autocomplete="off" />
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <label for="owner_name" class="col-5 col-lg-4 d-flex align-items-center">Owner
                                            Name:</label>
                                        <div class="col-7 col-lg-8">
                                            <input type="text" id="owner_name" name="owner_name"
                                                class="form-control shadow-none" v-model="customer.owner_name"
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
                                                    style="padding: 4px 8px;"><i class="fas fa-plus"></i></a>
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
                                                v-model="customer.phone" autocomplete="off" />
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <label for="address"
                                            class="col-5 col-lg-4 d-flex align-items-center">Address</label>
                                        <div class="col-7 col-lg-8">
                                            <textarea id="address" name="address" class="form-control shadow-none"
                                                v-model="customer.address" autocomplete="off"></textarea>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <label for="previous_due"
                                            class="col-5 col-lg-4 d-flex align-items-center">Previous_due:</label>
                                        <div class="col-7 col-lg-8">
                                            <input type="number" id="previous_due" name="previous_due"
                                                class="form-control shadow-none" v-model="customer.previous_due"
                                                autocomplete="off" />
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <label for="customer_type"
                                        class="col-5 col-lg-4 d-flex align-items-center">Customer Type:</label>
                                        <div class="col-7 col-lg-8">
                                            <input type="radio" id="customer_type" name="customer_type"
                                                v-model="customer.customer_type" value="retail" /> <label
                                                for="customer_type">Retail</label>
                                            <input type="radio" id="customer_type1" name="customer_type"
                                                v-model="customer.customer_type" value="wholesale" /> <label
                                                for="customer_type1">WholeSale</label>
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
                                <button type="submit" class="btn btn-sm btn-outline-success shadow-none">
                                    Save Customer
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <hr class="my-2" />
            <div class="col-12 col-lg-12">
                <vue-good-table :columns="columns" :rows="customers" :fixed-header="true" :pagination-options="{
                    enabled: true,
                    perPage: 15,
                }" :search-options="{
    enabled: true,
}" :line-numbers="true" styleClass="vgt-table" max-height="550px">
                    <template slot="table-row" slot-scope="props">
                        <span v-if="props.column.field == 'before'">
                            <button class="btn btn-sm btn-outline-primary shadow-none" @click="editRow(props.row)">
                                Edit
                            </button>
                            <button class="btn btn-sm btn-outline-danger shadow-none" @click="deleteRow(props.row.id)">
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
                    field: "customer_code",
                },
                {
                    label: "Customer Name",
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
            customers: [],
            cities: [],
            customer: {
                id: "",
                name: "",
                phone: "",
                owner_name: "",
                city_id: 1,
                address: "",
                previous_due: 0.00,
                customer_type: "retail",
                image: "",
            },
            selectedCity: {
                id: "",
                name: ""
            },
            customer_code: "",
            user_id: null,
            imageSrc: location.origin + "/no-image.jpg",
        };
    },
    created() {
        this.user_id = localStorage.getItem("user_id");
        this.getCustomer();
        this.getCity();
        this.logOut();
    },
    methods: {
        getCity() {
            axios.get("/api/get_city").then((res) => {
                this.cities = res.data;
            });
        },
        getCustomer() {
            axios.get("/api/get_customer").then((res) => {
                this.customers = res.data.customers;
                this.customer_code = res.data.gen_code;
            });
        },

        saveCustomer(event) {
            if (this.customer.name == "") {
                alert("Name Field is Empty");
                return;
            }
            if (this.customer.owner_name == "") {
                alert("Owner Name Field is Empty");
                return;
            }
            if (this.customer.phone == "") {
                alert("Phone Field is Empty");
                return;
            }
            if (this.customer.city_id == "") {
                alert("City Field is Empty");
                return;
            }

            let formdata = new FormData(event.target)
            formdata.append("image", this.customer.image)
            formdata.append("id", this.customer.id)
            formdata.append("city_id", this.selectedCity.id)
            axios
                .post(location.origin + "/api/save_customer", formdata)
                .then((res) => {
                    alert(res.data);
                    this.clearData();
                    this.getCustomer();
                });
        },

        editRow(val) {
            this.customer = {
                id: val.id,
                name: val.name,
                owner_name: val.owner_name,
                phone: val.phone,
                address: val.address,
                previous_due: val.previous_due,
                customer_type: val.customer_type,
            };
            this.customer_code = val.customer_code
            this.selectedCity = {
                id: val.city_id,
                name: val.city_name
            }

            this.imageSrc = val.image ? location.origin + "/" + val.image : location.origin + "/no-image.jpg"
        },

        deleteRow(id) {
            if (confirm("Are you sure")) {
                axios.get("/api/delete_customer/" + id).then((res) => {
                    alert(res.data);
                    this.getCustomer();
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
                        this.customer.image = event.target.files[0];
                    } else {
                        alert(`This image ${img.width} X ${img.width} but require image 200 X 200`);
                    }
                }
            }
        },

        clearData() {
            this.customer = {
                id: "",
                name: "",
                phone: "",
                owner_name: "",
                address: "",
                previous_due: 0.00,
                customer_type: "retail",
                image: "",
            };
            this.selectedCity = {
                id: "",
                name: ""
            }
            this.getCustomer()
            this.imageSrc = location.origin + "/no-image.jpg"
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

    mounted() {
        document.title = "Customer Page";
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
