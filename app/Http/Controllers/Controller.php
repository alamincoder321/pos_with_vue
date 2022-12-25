<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\Quotation;
use App\Models\Sale;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


    public function imageUpload($request, $name, $directory)
    {
        $doUpload = function ($image) use ($directory) {
            $name = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
            $extention = $image->getClientOriginalExtension();
            $imageName = $name . '_' . uniqId() . '.' . $extention;
            $image->move($directory, $imageName);
            return $directory . '/' . $imageName;
        };

        if (!empty($name) && $request->hasFile($name)) {
            $file = $request->file($name);
            if (is_array($file) && count($file)) {
                $imagesPath = [];
                foreach ($file as $key => $image) {
                    $imagesPath[] = $doUpload($image);
                }
                return $imagesPath;
            } else {
                return $doUpload($file);
            }
        }

        return false;
    }

    public function generateCode($model, $prefix = '')
    {
        $code = "00001";
        $model = '\\App\\Models\\' . $model;
        $num_rows = $model::count();
        if ($num_rows != 0) {
            $newCode = $num_rows + 1;
            $zeros = ['0', '00', '000', '0000', '00000'];
            $code = strlen($newCode) > count($zeros) ? $newCode : $zeros[count($zeros) - strlen($newCode)] . $newCode;
        }
        return $prefix . $code;
    }

    function invoiceNumberPurchase()
    {
        $purchase = Purchase::latest()->first();
        if (empty($purchase->invoice)) {
            $invoice = date('Y') . '00001';
            return $invoice;
        } else {
            return $purchase->invoice +1;
        }
    }
    function invoiceNumberSale()
    {
        $sale = Sale::latest()->first();
        if (empty($sale->invoice)) {
            $invoice = date('Y') . '00001';
            return $invoice;
        } else {
            return $sale->invoice +1;
        }
    }
    function invoiceNumberQuotation()
    {
        $quotation = Quotation::latest()->first();
        if (empty($quotation->invoice)) {
            $invoice = date('Y') . '00001';
            return $invoice;
        } else {
            return $quotation->invoice +1;
        }
    }
}
