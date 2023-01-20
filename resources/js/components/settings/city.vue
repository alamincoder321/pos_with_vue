<template>
    <div class="container-fluid px-4">
        <div class="row">
            <div class="col-12 mt-3">
                <div class="card" :style="{display: useraccess.includes('city.store')?'':'none'}">
                    <div class="card-header">
                        <h4 class="card-title">City Create</h4>
                    </div>
                    <form @submit.prevent="saveCity">
                        <div class="card-body">
                            <div class="row d-flex justify-content-center">
                                <div class="col-12 col-lg-8">
                                    <div class="row mt-2">
                                        <label for="name" class="col-4 col-lg-3 d-flex align-items-center">Name:</label>
                                        <div class="col-8 col-lg-9">
                                            <input type="text" id="name" name="name" class="form-control shadow-none"
                                                v-model="city.name" autocomplete="off" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group text-center mt-3">
                                <button type="button" class="btn btn-outline-secondary btn-sm shadow-none"
                                    @click="clearData">
                                    Reset
                                </button>
                                <button type="submit" class="btn btn-sm btn-outline-success shadow-none">
                                    Save City
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <hr class="my-2" />
            <div class="col-12 col-lg-12">
                <vue-good-table :columns="columns" :rows="cities" :fixed-header="true" :pagination-options="{
                                    enabled: true,
                                    perPage: 15,
                                }" :search-options="{
                    enabled: true,
                }" :line-numbers="true" styleClass="vgt-table" max-height="550px">
                    <template slot="table-row" slot-scope="props">
                        <span v-if="props.column.field == 'before'">
                            <button :style="{display: useraccess.includes('city.edit')?'':'none'}" class="btn btn-sm btn-outline-primary shadow-none" @click="editRow(props.row)">
                                Edit
                            </button>
                            <button :style="{display: useraccess.includes('city.delete')?'':'none'}" class="btn btn-sm btn-outline-danger shadow-none" @click="deleteRow(props.row.id)">
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
                    label: "City Name",
                    field: "name",
                },
                {
                    label: "Action",
                    field: "before",
                },
            ],
            cities: [],
            city: {
                id: "",
                name: "",
            },
            useraccess: [],
            user_id: null,
        };
    },
    created() {
        this.user_id = localStorage.getItem("user_id");
        this.getCity();
        this.getPermission();
        this.logOut();
    },
    methods: {
        getCity() {
            axios.get("/api/get-city").then((res) => {
                this.cities = res.data;
            });
        },

        saveCity(event) {
            if (this.city.name == "") {
                alert("Name Field is Empty");
                return;
            }

            let formdata = new FormData(event.target)
            formdata.append("id", this.city.id)
            axios
                .post(location.origin + "/api/save-city", formdata)
                .then((res) => {
                    alert(res.data);
                    this.clearData();
                    this.getCity();
                });
        },

        editRow(val) {
            this.city = {
                id: val.id,
                name: val.name,
            };
        },

        deleteRow(id) {
            if (confirm("Are you sure")) {
                axios.get("/api/delete-city/" + id).then((res) => {
                    alert(res.data);
                    this.getCity();
                });
            }
        },
        clearData() {
            this.city = {
                id: "",
                name: "",
            };
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
        useraccess(){
            this.useraccess.includes("city.index")?"":location.href = "/unauthorize"
        }
    },

    mounted() {
        document.title = "City Page";
    },
};
</script>
