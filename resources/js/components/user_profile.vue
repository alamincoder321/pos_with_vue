<template>
    <div class="container-fluid px-4">
        <div class="row">
            <div class="col-12 mt-3">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">User Profile</h4>
                    </div>
                    <form @submit.prevent="saveUser">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 col-lg-9">
                                    <div class="row mt-2">
                                        <label for="name" class="col-5 col-lg-4 d-flex align-items-center">Name:</label>
                                        <div class="col-7 col-lg-8">
                                            <input type="text" id="name" name="name" class="form-control shadow-none"
                                                v-model="user.name" autocomplete="off" />
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <label for="username" class="col-5 col-lg-4 d-flex align-items-center">User
                                            Name:</label>
                                        <div class="col-7 col-lg-8">
                                            <input type="text" id="username" name="username"
                                                class="form-control shadow-none" v-model="user.username"
                                                autocomplete="off" />
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <label for="email"
                                            class="col-5 col-lg-4 d-flex align-items-center">Email:</label>
                                        <div class="col-7 col-lg-8">
                                            <input type="email" id="email" name="email" class="form-control shadow-none"
                                                v-model="user.email" autocomplete="off" />
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <label for="password"
                                            class="col-5 col-lg-4 d-flex align-items-center">Password:</label>
                                        <div class="col-7 col-lg-8">
                                            <input type="password" id="password" name="password"
                                                class="form-control shadow-none" v-model="user.password"
                                                autocomplete="off" />
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
                                <button type="submit"
                                    class="userSave btn btn-sm btn-outline-success shadow-none">
                                    Update User
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
            columns: [
                {
                    label: "Name",
                    field: "name",
                },
                {
                    label: "User Name",
                    field: "username",
                },
                {
                    label: "Email",
                    field: "email",
                    type: "email",
                },
                {
                    label: "Action",
                    field: "before",
                },
            ],
            user: {
                id: "",
                name: "",
                email: "",
                username: "",
                password: "",
                image: "",
            },
            user_id: null,
            imageSrc: location.origin + "/no-image.jpg",
        };
    },
    created() {
        this.user_id = localStorage.getItem("user_id");
        this.getUser();
        this.logOut();
    },
    methods: {
        getUser() {
            axios.get(location.origin+"/api/edit_user/"+this.user_id).then((res) => {
                this.user = {
                    id      : res.data[0].id,
                    name    : res.data[0].name,
                    email   : res.data[0].email,
                    username: res.data[0].username,
                    image   : "",
                }
                this.imageSrc = res.data[0].image?location.origin + res.data[0].image:location.origin + "/no-image.jpg"
            });
        },

        saveUser(event) {
            if (this.user.name == "") {
                alert("Name field is Empty");
                return;
            }
            if (this.user.username == "") {
                alert("Username field is Empty");
                return;
            }
            if (this.user.email == "") {
                alert("Email field is Empty");
                return;
            }
            if (this.user.password == "") {
                alert("Password field is Empty");
                return;
            }

            let formdata = new FormData(event.target)
            formdata.append("image", this.user.image)
            formdata.append("id", this.user.id)
            axios
                .post(location.origin + "/api/save_user", formdata)
                .then((res) => {
                    alert(res.data);
                    this.getUser();
                });
        },

        imageUrl(event) {
            if (event.target.files[0]) {
                let img = new Image()
                img.src = window.URL.createObjectURL(event.target.files[0]);
                img.onload = () => {
                    if (img.width === 200 && img.height === 200) {
                        this.imageSrc = window.URL.createObjectURL(event.target.files[0]);
                        this.user.image = event.target.files[0];
                    } else {
                        alert(`This image ${img.width} X ${img.width} but require image 200 X 200`);
                    }
                }
            }
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
        document.title = "User Profile Page";
    },
};
</script>
