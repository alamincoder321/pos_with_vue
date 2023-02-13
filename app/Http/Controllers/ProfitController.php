<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProfitController extends Controller
{
    public function getProfit()
    {
        $today = date("Y-m-d");
        $month = date("m");
        $year = date("Y");
        // get data
        $totalData = [];

        //daywise sale
        $saleToday = DB::select("SELECT 
                            IFNULL(SUM(sm.total), 00) AS totalamount
                            FROM sales sm
                            WHERE sm.date = ?", [$today]);
        $totalData["todaySale"] = $saleToday[0]->totalamount;

        //monthwise sale
        $monthly = DB::select("SELECT 
                        IFNULL(SUM(sm.total), 00) AS totalamount
                        FROM sales sm
                        WHERE month(sm.date) = ?", [$month]);

        $totalData["monthlySale"] = number_format($monthly[0]->totalamount, 2, '.', '');

        //yearwise sale
        $allMonth = [];
        $yearlyAmount = 0;
        for ($i=1; $i <= 12; $i++) {
            $yearMonth = $year . sprintf("%02d", $i);
            $yearly = DB::select("SELECT 
                            IFNULL(SUM(sm.total), 00) AS totalamount
                            FROM sales sm
                            WHERE extract(year_month from sm.date) = ?", [$yearMonth]);
            $allMonth[$year .'-'. sprintf("%02d", $i)] = number_format($yearly[0]->totalamount, 0,'','');
            $yearlyAmount += $yearly[0]->totalamount;
        }
        $totalData["yearlySale"] = number_format($yearlyAmount, 2, '.', '');

        $totalData["allMonth"] = array_keys($allMonth);
        $totalData["allMonthValue"] = array_values($allMonth);
        
        return response()->json($totalData);
    }
}
