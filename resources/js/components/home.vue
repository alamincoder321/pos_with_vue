<template>
    <div class="container-fluid px-4">
        <h1 class="mt-4">Dashboard</h1>
        <div class="row">
            <div class="col-xl-4 col-md-6">
                <div class="card bg-primary text-center text-white mb-4">
                    <div class="card-body">{{ todaySale }}</div>
                    <div class="card-footer d-flex align-items-center justify-content-center">
                        <a class="small text-white text-decoration-none stretched-link">Today Sales</a>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-6">
                <div class="card bg-info text-center text-white mb-4">
                    <div class="card-body">{{monthlySale}}</div>
                    <div class="card-footer d-flex align-items-center justify-content-center">
                        <a class="small text-white text-decoration-none stretched-link">Monthly Sales</a>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-6">
                <div class="card bg-success text-center text-white mb-4">
                    <div class="card-body">{{yearlySale}}</div>
                    <div class="card-footer d-flex align-items-center justify-content-center">
                        <a class="small text-white text-decoration-none stretched-link">Yearly Sales</a>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-6">
                <div class="card bg-danger text-center text-white mb-4">
                    <div class="card-body">2500.00</div>
                    <div class="card-footer d-flex align-items-center justify-content-center">
                        <a class="small text-white text-decoration-none stretched-link">Today Collection</a>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-6">
                <div class="card bg-warning text-center text-white mb-4">
                    <div class="card-body">2500.00</div>
                    <div class="card-footer d-flex align-items-center justify-content-center">
                        <a class="small text-white text-decoration-none stretched-link">Monthly Collection</a>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-6">
                <div class="card bg-secondary text-center text-white mb-4">
                    <div class="card-body">2500.00</div>
                    <div class="card-footer d-flex align-items-center justify-content-center">
                        <a class="small text-white text-decoration-none stretched-link">Yearly Collection</a>
                    </div>
                </div>
            </div>
            <div class="col-xl-6 col-md-6">
                <div class="card bg-secondary text-center text-white mb-4">
                    <div class="card-body">2500.00</div>
                    <div class="card-footer d-flex align-items-center justify-content-center">
                        <a class="small text-white text-decoration-none stretched-link">Yearly Collection</a>
                    </div>
                </div>
            </div>
            <div class="col-xl-6 col-md-6">
                <div class="card bg-secondary text-center text-white mb-4">
                    <div class="card-body">2500.00</div>
                    <div class="card-footer d-flex align-items-center justify-content-center">
                        <a class="small text-white text-decoration-none stretched-link">Yearly Collection</a>
                    </div>
                </div>
            </div>

            <hr />

            <!-- chart list -->
            <div class="col-lg-12" v-if="searchType=='year'">
                <h3 class="m-0 text-center">This Year Sales Amount</h3>
                <GChart type="ColumnChart" :data="yearlyData" :options="yearlychartOptions" />
            </div>
            <div class="col-lg-12" v-else>
                <h3 class="m-0 text-center">This Month Sales Amount</h3>
                <GChart type="ColumnChart" :data="monthlyData" :options="monthlychartOptions" />
            </div>
            <div class="col-12 mt-2">
                <div class="d-flex justify-content-center">
                    <button class="btn btn-warning shadow-none" @click="searchType = 'year'">Yearly</button>
                    <button class="btn btn-primary shadow-none" @click="searchType = 'month'">Monthly</button>
                </div>
            </div>

            <div class="col-lg-7 my-3">
                    <h3 class="m-0 text-center">Top Product List</h3>
                    <GChart style="border:1px solid gray;" type="PieChart" :data="topData" :options="topchartOptions" />
            </div>
            <div class="col-lg-1"></div>
            <div class="col-lg-4 mt-3">
                <h3 class="m-0 text-center">Top Customer List</h3>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Sl</th>
                            <th>Customer Name</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item, index) in customers">
                            <td>{{ index + 1 }}</td>
                            <td>{{ item.customer_name }}</td>
                            <td>{{ item.total_amount }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
import moment from 'moment';
import { GChart } from 'vue-google-charts/legacy'
export default {
    components: {
        GChart
    },
    data() {
        return {
            searchType: 'year',
            monthlyData: [
                ['Month', 'Sales'],
            ],
            monthlychartOptions: {
                chart: {
                    title: 'Monthly Sales Chart',
                }
            },
            yearlyData: [
                ['Year', 'Sales'],
            ],
            yearlychartOptions: {
                chart: {
                    title: 'Yearly Sales Chart',
                }
            },
            topData: [
                ['Year', 'Sales'],
            ],
            topchartOptions: {
                chart: {
                    title: 'Yearly Sales Chart',
                }
            },
            customers:[],

            todaySale:0,
            monthlySale:0,
            yearlySale:0,
            useraccess: [],
            user_id: null,

        }
    },
    async created() {
        this.user_id = localStorage.getItem("user_id");
        await this.getProfit();
        this.getPermission();
        this.logOut();
    },
    methods: {
        async getProfit() {
            await axios.get(location.origin + "/api/profit")
                .then(res => {
                    res.data.monthlyRecord.forEach(data => {
                        this.monthlyData.push(data)
                    })
                    res.data.yearlyRecord.forEach(data => {
                        this.yearlyData.push(data)
                    })
                    res.data.topSold.forEach(p => {
                        this.topData.push([p.product_name, parseFloat(p.qty)]);
                    })

                    this.customers   = res.data.topCustomer;
                    this.todaySale   = res.data.todaySale[0].totalamount;
                    this.monthlySale = res.data.monthlySale[0].totalamount;
                    this.yearlySale  = res.data.yearlySale[0].totalamount;
                })

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
            this.useraccess.includes("dashboard") ? "" : location.href = "/unauthorize"
        }
    },
    mounted() {
        document.title = "Dashboard"
    },
};
</script>
