<template>
    <div class="container-fluid px-4">
        <div class="row">
            <div class="col-12 mt-3">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Company Profile</h4>
                    </div>
                    <form @submit.prevent="saveCompany">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 col-lg-9">
                                    <div class="row mt-2">
                                        <label for="name" class="col-5 col-lg-4 d-flex align-items-center">Company
                                            Name:</label>
                                        <div class="col-7 col-lg-8">
                                            <input type="text" id="name" name="name" class="form-control shadow-none"
                                                v-model="company.name" autocomplete="off" />
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <label for="owner_name" class="col-5 col-lg-4 d-flex align-items-center">Owner
                                            Name:</label>
                                        <div class="col-7 col-lg-8">
                                            <input type="text" id="owner_name" name="owner_name"
                                                class="form-control shadow-none" v-model="company.owner_name"
                                                autocomplete="off" />
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <label for="phone" class="col-5 col-lg-4 d-flex align-items-center">
                                            Phone:</label>
                                        <div class="col-7 col-lg-8">
                                            <input type="text" id="phone" name="phone" class="form-control shadow-none"
                                                v-model="company.phone" autocomplete="off" />
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <label for="address"
                                            class="col-5 col-lg-4 d-flex align-items-center">Address:</label>
                                        <div class="col-7 col-lg-8">
                                            <textarea id="address" name="address" class="form-control shadow-none"
                                                v-model="company.address" autocomplete="off"></textarea>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <label for="email"
                                            class="col-5 col-lg-4 d-flex align-items-center">Eamil:</label>
                                        <div class="col-7 col-lg-8">
                                            <input type="email" id="email" name="email" class="form-control shadow-none"
                                                v-model="company.email" autocomplete="off" />
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <label for="facebook"
                                            class="col-5 col-lg-4 d-flex align-items-center">Facebook:</label>
                                        <div class="col-7 col-lg-8">
                                            <input type="url" id="facebook" name="facebook"
                                                class="form-control shadow-none" v-model="company.facebook"
                                                autocomplete="off" />
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <label for="instagram"
                                            class="col-5 col-lg-4 d-flex align-items-center">Instagram:</label>
                                        <div class="col-7 col-lg-8">
                                            <input type="url" id="instagram" name="instagram"
                                                class="form-control shadow-none" v-model="company.instagram"
                                                autocomplete="off" />
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <label for="linkedin"
                                            class="col-5 col-lg-4 d-flex align-items-center">Linkedin:</label>
                                        <div class="col-7 col-lg-8">
                                            <input type="url" id="linkedin" name="linkedin"
                                                class="form-control shadow-none" v-model="company.linkedin"
                                                autocomplete="off" />
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <label for="twitter"
                                            class="col-5 col-lg-4 d-flex align-items-center">Twitter:</label>
                                        <div class="col-7 col-lg-8">
                                            <input type="url" id="twitter" name="twitter"
                                                class="form-control shadow-none" v-model="company.twitter"
                                                autocomplete="off" />
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <label for="paper_type"
                                            class="col-5 col-lg-4 d-flex align-items-center"></label>
                                        <div class="col-7 col-lg-8">
                                            <input type="radio" id="paper_type1" name="paper_type" value="1"
                                                :checked="company.paper_type == 1 ? true : false"> <label
                                                for="paper_type1">A3</label>
                                            <input type="radio" id="paper_type2" name="paper_type" value="2"
                                                :checked="company.paper_type == 2 ? true : false"> <label
                                                for="paper_type2">A4</label>
                                            <input type="radio" id="paper_type3" name="paper_type" value="3"
                                                :checked="company.paper_type == 3 ? true : false"> <label
                                                for="paper_type3">A5</label>
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
                            <div class="form-group text-center mt-3"
                                :style="{ display: useraccess.includes('companyprofile.store') ? '' : 'none' }">
                                <button type="submit"
                                    class="userSave btn btn-sm btn-outline-success shadow-none">
                                    Save Company Profile
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            company: [],
            user_id: null,
            imageSrc: "",
            useraccess: [],
        };
    },
    created() {
        this.user_id = localStorage.getItem("user_id");
        this.getCompany();
        this.logOut();
        this.getPermission()
    },
    methods: {
        getCompany() {
            axios.get("/api/get-company-profile").then((res) => {
                this.company = res.data;
                this.imageSrc = res.data.company_logo ? res.data.company_logo : location.origin + "/no-image.jpg";
            });
        },

        saveCompany(event) {
            if (this.company.name == "") {
                alert("Name field is Empty");
                return;
            }
            if (this.company.phone == "") {
                alert("Phone field is Empty");
                return;
            }
            if (this.company.address == "") {
                alert("Address field is Empty");
                return;
            }
            let formdata = new FormData(event.target)
            formdata.append("company_logo", this.company.company_logo)
            formdata.append("id", this.company.id)
            axios
                .post(location.origin + "/api/save-company", formdata)
                .then((res) => {
                    this.$toastr.s(res.data, "Success!");
                    this.getCompany();
                });
        },

        imageUrl(event) {
            if (event.target.files[0]) {
                let img = new Image()
                img.src = window.URL.createObjectURL(event.target.files[0]);
                img.onload = () => {
                    if (img.width === 200 && img.height === 200) {
                        this.imageSrc = window.URL.createObjectURL(event.target.files[0]);
                        this.company.company_logo = event.target.files[0];
                    } else {
                        alert(`This image ${img.width} X ${img.width} but require image 200 X 200`);
                    }
                }
            }
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
        useraccess() {
            this.useraccess.includes("companyprofile.index") ? "" : location.href = "/unauthorize"
        }
    },
    mounted() {
        document.title = "Company Profile Page";
    },
};
</script>
