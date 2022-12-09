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
        if($id != null){
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
        if($id != null){
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
}
