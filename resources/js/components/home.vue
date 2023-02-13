<template>
    <div class="container-fluid px-4">
        <h1 class="mt-4">Dashboard</h1>
        <div class="row">
            <div class="col-xl-4 col-md-6">
                <div class="card bg-primary text-center text-white mb-4">
                    <div class="card-body">{{ profit.todaySale }}</div>
                    <div class="card-footer d-flex align-items-center justify-content-center">
                        <a class="small text-white text-decoration-none stretched-link">Today Sales</a>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-6">
                <div class="card bg-info text-center text-white mb-4">
                    <div class="card-body">{{ profit.monthlySale }}</div>
                    <div class="card-footer d-flex align-items-center justify-content-center">
                        <a class="small text-white text-decoration-none stretched-link">Monthly Sales</a>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-6">
                <div class="card bg-success text-center text-white mb-4">
                    <div class="card-body">{{ profit.yearlySale }}</div>
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
            <div class="col-lg-8 offset-lg-2">
                <Bar :options="chartOptions" :data="chartData" />
            </div>
        </div>
    </div>
</template>

<script>
import moment from 'moment';
import { Bar } from 'vue-chartjs'
import { Chart as ChartJS, Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale } from 'chart.js'

ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale)
export default {
    name: 'BarChart',
    components: { Bar },
    data() {
        return {
            chartData: {
                labels: [],
                datasets: [
                    {
                        label: "Sales Amount",
                        backgroundColor: '#f87979',
                        data: []
                    }
                ]
            },
            chartOptions: {
                responsive: true
            },
            profit: {},
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
                    this.profit = res.data;
                    res.data.allMonth.map(v =>
                        this.chartData.labels.push(moment(v).format("MMMM"))
                    )
                    res.data.allMonthValue.map(v =>
                        this.chartData.datasets[0].data.push(v)
                    )
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
