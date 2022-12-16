<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ModelTable extends Model
{
    use HasFactory;

    public static function customerDue($id = null)
    {
        $clauses = "";
        if ($id != null) {
            $clauses .= "WHERE c.id = $id";
        }
        return DB::select("SELECT
                        c.name,
                        c.customer_code,
                        c.phone,
                        c.address,
                        (
                        SELECT
                            IFNULL(SUM(sm.total),
                            0)
                        FROM
                            sales AS sm
                        WHERE
                            sm.customer_id = c.id
                    ) AS billAmount,
                    (
                        SELECT
                            IFNULL(SUM(sm.paid),
                            0)
                        FROM
                            sales AS sm
                        WHERE
                            sm.customer_id = c.id
                    ) AS paidAmount,
                    (
                        SELECT
                            IFNULL(SUM(cp.payment_amount),
                            0)
                        FROM
                            customer_payments AS cp
                        WHERE
                            cp.customer_id = c.id
                    ) AS customerPaymentAmount,
                    (
                        SELECT
                            (billAmount+c.previous_due) - (paidAmount+customerPaymentAmount)
                    ) AS dueAmount
                    FROM
                        customers AS c $clauses");
    }

    // Supplier due
    public static function supplierDue($id = null)
    {
        $clauses = "";
        if ($id != null) {
            $clauses .= "WHERE s.id = $id";
        }
        return DB::select("SELECT
                        s.name,
                        s.supplier_code,
                        s.phone,
                        s.address,
                        (
                        SELECT
                            IFNULL(SUM(pm.total),
                            0)
                        FROM
                            purchases AS pm
                        WHERE
                            pm.supplier_id = s.id
                    ) AS billAmount,
                    (
                        SELECT
                            IFNULL(SUM(pm.paid),
                            0)
                        FROM
                            purchases AS pm
                        WHERE
                            pm.supplier_id = s.id
                    ) AS paidAmount,
                    (
                        SELECT
                            IFNULL(SUM(sp.payment_amount),
                            0)
                        FROM
                            supplier_payments AS sp
                        WHERE
                            sp.supplier_id = s.id
                    ) AS supplierPaymentAmount,
                    (
                        SELECT
                            (billAmount+s.previous_due) - (paidAmount+supplierPaymentAmount)
                    ) AS dueAmount
                    FROM
                        suppliers AS s $clauses");
    }

    public static function Stock($id = null)
    {
        $clauses = "";
        if ($id != null) {
            $clauses .= "WHERE p.id = $id";
        }

        return DB::select("SELECT
        (IFNULL(pin.purchase_qty, 0) + IFNULL(pin.sale_return_qty, 0) - IFNULL(pin.purchase_return_qty, 0) - IFNULL(pin.sale_qty, 0) - IFNULL(pin.damage_qty, 0)) AS stock,
        p.*, un.name AS unit_name FROM products AS p LEFT JOIN product_inventories AS pin ON p.id = pin.product_id LEFT JOIN units AS un ON un.id = p.unit_id $clauses");
    }

    public static function bankBalance($id=null, $fromDate=null, $toDate= null)
    {
        return DB::select("SELECT
                        b.*,
                        (
                        SELECT
                            IFNULL(SUM(sm.paid),
                            0)
                        FROM
                            sales AS sm
                        WHERE
                            sm.payment_type = 'bank' AND sm.account_id = b.id
                            " . ($fromDate==null ? "" : " AND sm.date BETWEEN '$fromDate' AND '$toDate'") . "
                    ) AS saleamount,
                        (
                        SELECT
                            IFNULL(SUM(pm.paid),
                            0)
                        FROM
                            purchases AS pm
                        WHERE
                            pm.payment_type = 'bank' AND pm.account_id = b.id
                            " . ($fromDate==null ? "" : " AND pm.date BETWEEN '$fromDate' AND '$toDate'") . "
                    ) AS purchaseamount,
                    (
                        SELECT
                            IFNULL(SUM(cp.payment_amount),
                            0)
                        FROM
                            customer_payments AS cp
                        WHERE
                            cp.payment_type = 'bank' AND cp.bank_id = b.id
                            " . ($fromDate==null ? "" : " AND cp.date BETWEEN '$fromDate' AND '$toDate'") . "
                    ) AS customeramount,
                    (
                        SELECT
                            IFNULL(SUM(sp.payment_amount),
                            0)
                        FROM
                            supplier_payments AS sp
                        WHERE
                            sp.payment_type = 'bank' AND sp.bank_id = b.id
                            " . ($fromDate==null ? "" : " AND sp.date BETWEEN '$fromDate' AND '$toDate'") . "
                    ) AS supplieramount,
                    (
                        SELECT
                            ifnull(sum(trb.amount),
                            0)
                        FROM
                            transactions AS trb
                        WHERE
                            trb.transaction_type = 'deposit' AND trb.account_id = b.id
                            " . ($fromDate==null ? "" : " AND trb.date BETWEEN '$fromDate' AND '$toDate'") . "
                    ) AS depositamount,
                    (
                        SELECT
                        ifnull(sum(trbw.amount),
                            0)
                        FROM
                            transactions AS trbw
                        WHERE
                        trbw.transaction_type = 'withdraw' AND trbw.account_id = b.id
                            " . ($fromDate==null ? "" : " AND trbw.date BETWEEN '$fromDate' AND '$toDate'") . "
                    ) AS withdrawamount,
                    (SELECT (saleamount+customeramount+depositamount) - (purchaseamount+supplieramount+withdrawamount)) AS totalbalance
                    FROM
                        bank_accounts b 
                        WHERE 1 = 1
                        " . ($id==null ? "" : " AND b.id = '$id'") . "
                    ");
    }
}
