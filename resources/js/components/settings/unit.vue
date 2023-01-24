<template>
    <div class="container-fluid px-4">
        <div class="row">
            <div class="col-12 mt-3">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Unit Create</h4>
                    </div>
                    <form @submit.prevent="saveUnit">
                        <div class="card-body">
                            <div class="row d-flex justify-content-center">
                                <div class="col-12 col-lg-8">
                                    <div class="row mt-2">
                                        <label for="name" class="col-4 col-lg-3 d-flex align-items-center">Name:</label>
                                        <div class="col-8 col-lg-9">
                                            <input type="text" id="name" name="name" class="form-control shadow-none"
                                                v-model="unit.name" autocomplete="off" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group text-center mt-3">
                                <button type="button" class="btn btn-outline-secondary btn-sm shadow-none"
                                    @click="clearData">
                                    Reset
                                </button>
                                <button :style="{display: useraccess.includes('unit.store')?'':'none'}" type="submit" class="btn btn-sm btn-outline-success shadow-none">
                                    Save Unit
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <hr class="my-2" />
            <div class="col-12 col-lg-12">
                <vue-good-table :columns="columns" :rows="units" :fixed-header="true" :pagination-options="{
                                    enabled: true,
                                    perPage: 15,
                                }" :search-options="{
                    enabled: true,
                }" :line-numbers="true" styleClass="vgt-table" max-height="550px">
                    <template slot="table-row" slot-scope="props">
                        <span v-if="props.column.field == 'before'">
                            <button :style="{display: useraccess.includes('unit.edit')?'':'none'}" class="btn btn-sm btn-outline-primary shadow-none" @click="editRow(props.row)">
                                Edit
                            </button>
                            <button :style="{display: useraccess.includes('unit.delete')?'':'none'}" class="btn btn-sm btn-outline-danger shadow-none" @click="deleteRow(props.row.id)">
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
                    label: "Action",
                    field: "before",
                },
            ],
            units: [],
            unit: {
                id: "",
                name: "",
            },
            useraccess: [],
            user_id: null,
        };
    },
    created() {
        this.user_id = localStorage.getItem("user_id");
        this.getUnit();
        this.getPermission();
        this.logOut();
    },
    methods: {
        getUnit() {
            axios.get("/api/get-unit").then((res) => {
                this.units = res.data;
            });
        },

        saveUnit(event) {
            if (this.unit.name == "") {
                alert("Name Field is Empty");
                return;
            }

            let formdata = new FormData(event.target)
            formdata.append("id", this.unit.id)
            axios
                .post(location.origin + "/api/save-unit", formdata)
                .then((res) => {
                    this.$toastr.s(res.data, "Success!");
                    this.clearData();
                    this.getUnit();
                });
        },

        editRow(val) {
            this.unit = {
                id: val.id,
                name: val.name,
            };
        },

        deleteRow(id) {
            if (confirm("Are you sure")) {
                axios.get("/api/delete-unit/" + id).then((res) => {
                    this.$toastr.s(res.data, "Success!");
                    this.getUnit();
                });
            }
        },
        clearData() {
            this.unit = {
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
            this.useraccess.includes("unit.index")?"":location.href = "/unauthorize"
        }
    },
    mounted() {
        document.title = "Unit Page";
    },
};
</script>
