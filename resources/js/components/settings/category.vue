<template>
    <div class="container-fluid px-4">
        <div class="row">
            <div class="col-12 mt-3">
                <div class="card" :style="{display: useraccess.includes('category.store')?'':'none'}">
                    <div class="card-header">
                        <h4 class="card-title">Category Create</h4>
                    </div>
                    <form @submit.prevent="saveCategory">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 col-lg-9">
                                    <div class="row mt-2">
                                        <label for="name" class="col-5 col-lg-4 d-flex align-items-center">Name:</label>
                                        <div class="col-7 col-lg-8">
                                            <input type="text" id="name" name="name" class="form-control shadow-none"
                                                v-model="category.name" autocomplete="off" />
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <label for="description"
                                            class="col-5 col-lg-4 d-flex align-items-center">Description</label>
                                        <div class="col-7 col-lg-8">
                                            <textarea id="description" name="description"
                                                class="form-control shadow-none" v-model="category.description"
                                                autocomplete="off"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-lg-3 d-flex justify-content-center align-items-center">
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
                                <button :style="{display: useraccess.includes('category.store')?'':'none'}" type="submit" class="saveCategory btn btn-sm btn-outline-success shadow-none">
                                    Save Category
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <hr class="my-2" />
            <div class="col-12 col-lg-12">
                <vue-good-table :columns="columns" :rows="categories" :fixed-header="true" :pagination-options="{
                                    enabled: true,
                                    perPage: 15,
                                }" :search-options="{
                    enabled: true,
                }" :line-numbers="true" styleClass="vgt-table" max-height="550px">
                    <template slot="table-row" slot-scope="props">
                        <span v-if="props.column.field == 'before'">
                            <button :style="{display: useraccess.includes('category.edit')?'':'none'}" class="btn btn-sm btn-outline-primary shadow-none" @click="editRow(props.row)">
                                Edit
                            </button>
                            <button :style="{display: useraccess.includes('category.delete')?'':'none'}" class="btn btn-sm btn-outline-danger shadow-none" @click="deleteRow(props.row.id)">
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
                    label: "Name",
                    field: "name",
                },
                {
                    label: "Description",
                    field: "description",
                    type: "text",
                },
                {
                    label: "Action",
                    field: "before",
                },
            ],
            categories: [],
            category: {
                id: "",
                name: "",
                description: "",
                image: "",
            },
            user_id: null,
            useraccess: [],
            imageSrc: location.origin + "/no-image.jpg",
        };
    },
    created() {
        this.user_id = localStorage.getItem("user_id");
        this.getCategory();
        this.getPermission();
        this.logOut();
    },
    methods: {
        getCategory() {
            axios.get("/api/get_category").then((res) => {
                this.categories = res.data;
            });
        },

        saveCategory(event) {
            if (this.category.name == "") {
                alert("Name Field is Empty");
                return;
            }

            let formdata = new FormData(event.target)
            formdata.append("image", this.category.image)
            formdata.append("id", this.category.id)
            axios
                .post(location.origin + "/api/save_category", formdata)
                .then((res) => {
                    alert(res.data);
                    this.clearData();
                    this.getCategory();
                });
        },

        editRow(val) {
            this.category = {
                id: val.id,
                name: val.name,
                description: val.description,
            };

            this.imageSrc = val.image?location.origin+"/"+val.image:location.origin+"/no-image.jpg"
        },

        deleteRow(id) {
            if (confirm("Are you sure")) {
                axios.get("/api/delete_category/" + id).then((res) => {
                    alert(res.data);
                    this.getCategory();
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
                        this.category.image = event.target.files[0];
                    } else {
                        alert(`This image ${img.width} X ${img.width} but require image 200 X 200`);
                    }
                }
            }
        },

        clearData() {
            this.category = {
                id: "",
                name: "",
                description: "",
                image: "",
            };
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
            this.useraccess.includes("category.index")?"":location.href = "/unauthorize"
        }
    },
    mounted() {
        document.title = "Category Page";
    },
};
</script>
