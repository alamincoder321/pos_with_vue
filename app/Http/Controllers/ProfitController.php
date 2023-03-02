<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProfitController extends Controller
{
    public function getProfit()
    {
        $month = date("m");
        $year = date("Y");
        $today = date("Y-m-d");
        $dayNumber = date('t', mktime(0, 0, 0, $month, 1, $year));
        // get data
        // $totalData = [];

        //daywise sale
        $todaySale = DB::select("SELECT 
                            IFNULL(SUM(sm.total), 00) AS totalamount
                            FROM sales sm
                            WHERE sm.date = ?", [$today]);
        $monthlySale = DB::select("SELECT 
                            IFNULL(SUM(sm.total), 00) AS totalamount
                            FROM sales sm
                            WHERE MONTH(sm.date) = ?", [$month]);
        $yearlySale = DB::select("SELECT 
                            IFNULL(SUM(sm.total), 00) AS totalamount
                            FROM sales sm
                            WHERE YEAR(sm.date) = ?", [$year]);

        // monthly record
        $monthlyRecord = [];
        for ($i = 1; $i <= $dayNumber; $i++) {
            $date = $year . '-' . $month . '-' . sprintf("%02d", $i);
            $query = DB::select("SELECT 
                    IFNULL(SUM(sm.total), 00) AS totalamount
                    FROM sales sm
                    WHERE sm.date = ?", [$date]);

            $amount = (float)$query[0]->totalamount;

            $sale = [sprintf("%02d", $i), $amount];
            array_push($monthlyRecord, $sale);
        }

        // yearly record
        $yearlyRecord = [];
        for ($i = 1; $i <= 12; $i++) {
            $yearMonth = $year . sprintf("%02d", $i);
            $query = DB::select("SELECT 
                                IFNULL(SUM(sm.total), 00) AS totalamount
                                FROM sales sm
                                WHERE extract(year_month from sm.date) = ?", [$yearMonth]);


            $amount = (float)$query[0]->totalamount;
            $monthName = date("M", mktime(0, 0, 0, $i, 10));

            $sale = [$monthName, $amount];
            array_push($yearlyRecord, $sale);
        }

        // top sold product
        $topSold = DB::select("SELECT
                                p.name AS product_name,
                                SUM(sd.quantity) AS qty
                            FROM sale_details sd
                            JOIN products p ON p.id = sd.product_id
                            JOIN sales sm ON sm.id = sd.sale_id
                            GROUP BY product_name
                            LIMIT 5");
        // top sold product
        $topCustomer = DB::select("SELECT
                                    concat_ws('-', c.customer_code, c.name) as customer_name,
                                    IFNULL(SUM(sm.total),0) AS total_amount
                                FROM sales sm
                                JOIN customers c ON c.id = sm.customer_id
                                WHERE c.customer_type != 'G'
                                GROUP BY name
                                LIMIT 5");

        return response()->json([
            'monthlyRecord' => $monthlyRecord,
            'yearlyRecord'  => $yearlyRecord,
            'topSold'       => $topSold,
            'topCustomer'   => $topCustomer,
            'todaySale'     => $todaySale,
            'monthlySale'   => $monthlySale,
            'yearlySale'    => $yearlySale,
        ]);
    }
}
