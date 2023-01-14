<template>
    <div class="container-fluid px-4">
        <div class="row">
            <div class="col-12 mt-3">
                <div class="card" :style="{display: useraccess.includes('product.store')?'':'none'}">
                    <div class="card-header">
                        <h4 class="card-title">Product Create</h4>
                    </div>
                    <form @submit.prevent="saveProduct">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 col-lg-5">
                                    <div class="row mt-2">
                                        <label for="product_code"
                                            class="col-5 col-lg-4 d-flex align-items-center">Code:</label>
                                        <div class="col-7 col-lg-8">
                                            <input type="text" id="product_code" name="product_code" readonly
                                                class="form-control shadow-none" v-model="product.product_code"
                                                autocomplete="off" />
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <label for="category_id" class="col-5 col-lg-4 d-flex align-items-center">Category
                                            Name:</label>
                                        <div class="col-7 col-lg-8">
                                            <div class="input-group">
                                                <v-select label="name" :options="categories"
                                                    v-model="selectedCategory"></v-select>
                                                <a href="/categories" target="_blank" class="btn btn-success shadow-none"
                                                    style="padding: 4px 8px;"><i class="fas fa-plus"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <label for="brand_id" class="col-5 col-lg-4 d-flex align-items-center">Brand
                                            Name:</label>
                                        <div class="col-7 col-lg-8">
                                            <div class="input-group">
                                                <v-select label="name" name="brand_id" :options="brands"
                                                    v-model="selectedBrand"></v-select>
                                                <a href="/brands" target="_blank" class="btn btn-success shadow-none"
                                                    style="padding: 4px 8px;"><i class="fas fa-plus"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <label for="name" class="col-5 col-lg-4 d-flex align-items-center">Name:</label>
                                        <div class="col-7 col-lg-8">
                                            <input type="text" id="name" name="name" class="form-control shadow-none"
                                                v-model="product.name" autocomplete="off" />
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <label for="unit_id" class="col-5 col-lg-4 d-flex align-items-center">Unit
                                            Name:</label>
                                        <div class="col-7 col-lg-8">
                                            <div class="input-group">
                                                <v-select label="name" name="unit_id" :options="units"
                                                    v-model="selectedUnit"></v-select>
                                                <a href="/units" target="_blank" class="btn btn-success shadow-none"
                                                    style="padding: 4px 8px;"><i class="fas fa-plus"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-lg-5">
                                    <div class="row mt-2">
                                        <label for="re_order"
                                            class="col-5 col-lg-4 d-flex align-items-center">Re Order:</label>
                                        <div class="col-7 col-lg-8">
                                            <input type="number" id="re_order" name="re_order" class="form-control shadow-none"
                                                v-model="product.re_order" autocomplete="off" />
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <label for="purchase_price"
                                            class="col-5 col-lg-4 d-flex align-items-center">Purchase Price:</label>
                                        <div class="col-7 col-lg-8">
                                            <input type="number" id="purchase_price" name="purchase_price" class="form-control shadow-none"
                                                v-model="product.purchase_price" autocomplete="off" />
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <label for="selling_price"
                                            class="col-5 col-lg-4 d-flex align-items-center">Sale Price:</label>
                                        <div class="col-7 col-lg-8">
                                            <input type="number" id="selling_price" name="selling_price"
                                                class="form-control shadow-none" v-model="product.selling_price"
                                                autocomplete="off" />
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <label for="description"
                                            class="col-5 col-lg-4 d-flex align-items-center">Details:</label>
                                        <div class="col-7 col-lg-8">
                                            <textarea id="description" name="description" class="form-control shadow-none"
                                                v-model="product.description" autocomplete="off"></textarea>
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
                                <button :style="{display: useraccess.includes('product.store')?'':'none'}" type="submit" class="btn btn-sm btn-outline-success shadow-none">
                                    Save Product
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <hr class="my-2" />
            <div class="col-12 col-lg-12" style="overflow-x:auto;">
                <vue-good-table :columns="columns" :rows="products" :fixed-header="true" :pagination-options="{
                    enabled: true,
                    perPage: 15,
                }" :search-options="{
    enabled: true,
}" :line-numbers="true" styleClass="vgt-table" max-height="550px">
                    <template slot="table-row" slot-scope="props">
                        <span v-if="props.column.field == 'before'">
                            <button :style="{display: useraccess.includes('product.edit')?'':'none'}" class="btn btn-sm btn-outline-primary shadow-none" @click="editRow(props.row)">
                                Edit
                            </button>
                            <button :style="{display: useraccess.includes('product.delete')?'':'none'}" class="btn btn-sm btn-outline-danger shadow-none" @click="deleteRow(props.row.id)">
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
                    field: "product_code",
                },
                {
                    label: "Product Name",
                    field: "name",
                },
                {
                    label: "Brand",
                    field: "brand_name",
                },
                {
                    label: "Category",
                    field: "category_name",
                },
                {
                    label: "Selling Price",
                    field: "selling_price",
                },
                {
                    label: "Action",
                    field: "before",
                },
            ],
            products: [],
            brands: [],
            categories: [],
            units: [],
            product: {
                id: "",
                product_code: "",
                name: "",
                re_order: 0,
                purchase_price: "",
                selling_price: "",
                description: "",
                image: "",
            },
            selectedBrand: {
                id: "",
                name: "Select Brand"
            },
            selectedCategory: {
                id: "",
                name: "Select Category"
            },
            selectedUnit: {
                id: "",
                name: "Select Unit"
            },
            useraccess: [],
            user_id: null,
            imageSrc: location.origin + "/no-image.jpg",
        };
    },
    created() {
        this.user_id = localStorage.getItem("user_id");
        this.getProduct();
        this.getBrand();
        this.getCategory();
        this.getUnit();
        this.getPermission();
        this.logOut();
    },
    methods: {
        getBrand() {
            axios.get("/api/get_brand").then((res) => {
                this.brands = res.data;
                this.brands.unshift({id:0, name:"Select Brand"})
            });
        },
        getCategory() {
            axios.get("/api/get_category").then((res) => {
                this.categories = res.data;
                this.categories.unshift({id:0, name:"Select Category"})
            });
        },
        getUnit() {
            axios.get("/api/get_unit").then((res) => {
                this.units = res.data;
                this.units.unshift({id:0, name:"Select Unit"})
            });
        },
        getProduct() {
            axios.get("/api/get_product").then((res) => {
                this.products = res.data.products;
                this.product.product_code = res.data.gen_code;
            });
        },

        saveProduct(event) {
            if (this.product.name == "") {
                alert("Name Field is Empty");
                return;
            }
            if (this.product.purchase_price == "") {
                alert("Purchase Field is Empty");
                return;
            }
            if (this.product.selling_price == "") {
                alert("Selling price Field is Empty");
                return;
            }

            let formdata = new FormData(event.target)
            formdata.append("image", this.product.image)
            formdata.append("id", this.product.id)
            formdata.append("brand_id", this.selectedBrand.id)
            formdata.append("category_id", this.selectedCategory.id)
            formdata.append("unit_id", this.selectedUnit.id)
            axios
                .post(location.origin + "/api/save_product", formdata)
                .then((res) => {
                    alert(res.data);
                    this.clearData();
                    this.getProduct();
                });
        },

        editRow(val) {
            this.product = {
                id: val.id,
                product_code: val.product_code,
                name: val.name,
                re_order: val.re_order,
                purchase_price: val.purchase_price,
                selling_price: val.selling_price,
                description: val.description,
            };
            this.selectedBrand = {
                id: val.brand_id,
                name: val.brand_name
            }
            this.selectedCategory = {
                id: val.category_id,
                name: val.category_name
            }
            this.selectedUnit = {
                id: val.unit_id,
                name: val.unit_name
            }

            this.imageSrc = val.image ? location.origin + "/" + val.image : location.origin + "/no-image.jpg"
        },

        deleteRow(id) {
            if (confirm("Are you sure")) {
                axios.get("/api/delete_product/" + id).then((res) => {
                    alert(res.data);
                    this.getProduct();
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
                        this.product.image = event.target.files[0];
                    } else {
                        alert(`This image ${img.width} X ${img.width} but require image 200 X 200`);
                    }
                }
            }
        },

        clearData() {
            this.product = {
                id: "",
                name: "",
                re_order: 0,
                purchase_price: "",
                selling_price: "",
                description: "",
                image: "",
            };
            this.selectedBrand = {
                id: "",
                name: ""
            }
            this.selectedCategory = {
                id: "",
                name: ""
            }
            this.selectedUnit = {
                id: "",
                name: ""
            }
            this.getProduct()
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
            this.useraccess.includes("product.index")?"":location.href = "/unauthorize"
        }
    },
    mounted() {
        document.title = "Product Page";
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
