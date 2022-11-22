<template>
    <div class="container-fluid px-4">
        <div class="row">
            <div class="col-12 mt-3">
                <div class="card" :style="{display: useraccess.includes('employer.store')?'':'none'}">
                    <div class="card-header">
                        <h4 class="card-title">Employer Create</h4>
                    </div>
                    <form @submit.prevent="saveEmployer">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 col-lg-5">
                                    <div class="row mt-2">
                                        <label for="employer_code"
                                            class="col-5 col-lg-4 d-flex align-items-center">Code:</label>
                                        <div class="col-7 col-lg-8">
                                            <input type="text" id="employer_code" name="employer_code" readonly
                                                class="form-control shadow-none" v-model="employer_code"
                                                autocomplete="off" />
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <label for="name" class="col-5 col-lg-4 d-flex align-items-center">Name:</label>
                                        <div class="col-7 col-lg-8">
                                            <input type="text" id="name" name="name" class="form-control shadow-none"
                                                v-model="employer.name" autocomplete="off" />
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <label for="designation" class="col-5 col-lg-4 d-flex align-items-center">Designation:</label>
                                        <div class="col-7 col-lg-8">
                                            <input type="text" id="designation" name="designation"
                                                class="form-control shadow-none" v-model="employer.designation"
                                                autocomplete="off" />
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <label for="father_name"
                                            class="col-5 col-lg-4 d-flex align-items-center">Father Name:</label>
                                        <div class="col-7 col-lg-8">
                                            <input type="text" id="father_name" name="father_name" class="form-control shadow-none"
                                                v-model="employer.father_name" autocomplete="off" />
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <label for="mother_name"
                                            class="col-5 col-lg-4 d-flex align-items-center">Mother Name:</label>
                                        <div class="col-7 col-lg-8">
                                            <input type="text" id="mother_name" name="mother_name" class="form-control shadow-none"
                                                v-model="employer.mother_name" autocomplete="off" />
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <label for="dob"
                                            class="col-5 col-lg-4 d-flex align-items-center">DOB:</label>
                                        <div class="col-7 col-lg-8">
                                            <date-picker id="dob" name="dob" class="form-control shadow-none" v-model="employer.dob" :config="options"></date-picker>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <label for="phone"
                                            class="col-5 col-lg-4 d-flex align-items-center">Phone:</label>
                                        <div class="col-7 col-lg-8">
                                            <input type="text" id="phone" name="phone" class="form-control shadow-none"
                                                v-model="employer.phone" autocomplete="off" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-lg-5">
                                    <div class="row mt-2">
                                        <label for="department_id" class="col-5 col-lg-4 d-flex align-items-center">Department:</label>
                                        <div class="col-7 col-lg-8">
                                            <div class="input-group">
                                                <v-select label="name" name="department_id" :options="departments"
                                                    v-model="selectedDepartment"></v-select>
                                                <a href="/departments" target="_blank" class="btn btn-success shadow-none"
                                                    style="padding: 4px 8px;"><i class="fas fa-plus"></i></a>
                                            </div>
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
                                    <div class="row mt-2">
                                        <label for="address"
                                            class="col-5 col-lg-4 d-flex align-items-center">Address:</label>
                                        <div class="col-7 col-lg-8">
                                            <textarea id="address" name="address" class="form-control shadow-none"
                                                v-model="employer.address" autocomplete="off"></textarea>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <label for="join_date"
                                            class="col-5 col-lg-4 d-flex align-items-center">Join Date:</label>
                                        <div class="col-7 col-lg-8">
                                            <date-picker id="join_date" name="join_date" class="form-control shadow-none" v-model="employer.join_date" :config="options"></date-picker>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <label for="salary"
                                            class="col-5 col-lg-4 d-flex align-items-center">Salary:</label>
                                        <div class="col-7 col-lg-8">
                                            <input type="number" id="salary" name="salary"
                                                class="form-control shadow-none" v-model="employer.salary"
                                                autocomplete="off" />
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
                                <button :style="{display: useraccess.includes('employer.store')?'':'none'}" type="submit" class="btn btn-sm btn-outline-success shadow-none">
                                    Save Employer
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <hr class="my-2" />
            <div class="col-12 col-lg-12">
                <vue-good-table :columns="columns" :rows="employers" :fixed-header="true" :pagination-options="{
                    enabled: true,
                    perPage: 15,
                }" :search-options="{
    enabled: true,
}" :line-numbers="true" styleClass="vgt-table" max-height="550px">
                    <template slot="table-row" slot-scope="props">
                        <span v-if="props.column.field == 'before'">
                            <button :style="{display: useraccess.includes('employer.edit')?'':'none'}" class="btn btn-sm btn-outline-primary shadow-none" @click="editRow(props.row)">
                                Edit
                            </button>
                            <button :style="{display: useraccess.includes('employer.delete')?'':'none'}" class="btn btn-sm btn-outline-danger shadow-none" @click="deleteRow(props.row.id)">
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
            options: {
                format: 'DD/MM/YYYY',
                useCurrent: true,
            }, 
            columns: [
                {
                    label: "Code",
                    field: "employer_code",
                },
                {
                    label: "Employer Name",
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
                    label: "Salary",
                    field: "salary",
                    type: "number",
                },
                {
                    label: "Action",
                    field: "before",
                },
            ],
            employers  : [],
            cities     : [],
            departments: [],
            employer   : {
                id: "",
                name: "",
                designation: "",
                phone: "",
                father_name: "",
                mother_name: "",
                dob: new Date(),
                join_date: new Date(),
                address: "",
                salary: 0.00,
                image: "",
            },
            selectedCity: {
                id: "",
                name: ""
            },
            selectedDepartment: {
                id: "",
                name: ""
            },
            employer_code: "",
            useraccess: [],
            user_id: null,
            imageSrc: location.origin + "/no-image.jpg",
        };
    },
    created() {
        this.user_id = localStorage.getItem("user_id");
        this.getEmployer();
        this.getCity();
        this.getDepartment();
        this.getPermission();
        this.logOut();
    },
    methods: {
        getCity() {
            axios.get("/api/get_city").then((res) => {
                this.cities = res.data;
            });
        },
        getDepartment() {
            axios.get("/api/get_department").then((res) => {
                this.departments = res.data;
            });
        },
        getEmployer() {
            axios.get("/api/get_employer").then((res) => {
                this.employers = res.data.employers;
                this.employer_code = res.data.gen_code;
            });
        },

        saveEmployer(event) {
            if (this.employer.name == "") {
                alert("Name Field is Empty");
                return;
            }
            if (this.employer.owner_name == "") {
                alert("Owner Name Field is Empty");
                return;
            }
            if (this.employer.phone == "") {
                alert("Phone Field is Empty");
                return;
            }
            if (this.employer.city_id == "") {
                alert("City Field is Empty");
                return;
            }

            let formdata = new FormData(event.target)
            formdata.append("image", this.employer.image)
            formdata.append("id", this.employer.id)
            formdata.append("city_id", this.selectedCity.id)
            formdata.append("department_id", this.selectedDepartment.id)
            axios
                .post(location.origin + "/api/save_employer", formdata)
                .then((res) => {
                    alert(res.data);
                    this.clearData();
                    this.getEmployer();
                });
        },

        editRow(val) {
            this.employer = {
                id: val.id,
                name: val.name,
                designation: val.designation,
                phone: val.phone,
                father_name: val.father_name,
                mother_name: val.mother_name,
                address: val.address,
                salary: val.salary,
                dob: val.dob,
                join_date: val.join_date,
            };
            this.employer_code = val.employer_code
            this.selectedCity = {
                id: val.city_id,
                name: val.city_name
            }
            this.selectedDepartment = {
                id: val.department_id,
                name: val.department_name
            }

            this.imageSrc = val.image ? location.origin + "/" + val.image : location.origin + "/no-image.jpg"
        },

        deleteRow(id) {
            if (confirm("Are you sure")) {
                axios.get("/api/delete_employer/" + id).then((res) => {
                    alert(res.data);
                    this.getEmployer();
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
                        this.employer.image = event.target.files[0];
                    } else {
                        alert(`This image ${img.width} X ${img.width} but require image 200 X 200`);
                    }
                }
            }
        },

        clearData() {
            this.employer = {
                id: "",
                name: "",
                designation: "",
                phone: "",
                father_name: "",
                mother_name: "",
                dob: new Date(),
                join_date: new Date(),
                address: "",
                salary: 0.00,
                image: "",
            };
            this.selectedCity = {
                id: "",
                name: ""
            };
            this.selectedDepartment = {
                id: "",
                name: ""
            }
            this.getEmployer()
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
            this.useraccess.includes("employer.index")?"":location.href = "/unauthorize"
        }
    },
    mounted() {
        document.title = "Employer Page";
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
