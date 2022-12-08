<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\UserAccess;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            [
                "group_name" => "dashboard",
                "permission" => [
                    "dashboard",
                ],
            ],
            [
                "group_name" => "bankaccount",
                "permission" => [
                    "bankaccount.index",
                    "bankaccount.edit",
                    "bankaccount.store",
                    "bankaccount.delete",
                ],
            ],
            [
                "group_name" => "brand",
                "permission" => [
                    "brand.index",
                    "brand.edit",
                    "brand.store",
                    "brand.delete",
                ],
            ],
            [
                "group_name" => "category",
                "permission" => [
                    "category.index",
                    "category.edit",
                    "category.store",
                    "category.delete",
                ],
            ],
            [
                "group_name" => "companyprofile",
                "permission" => [
                    "companyprofile.index",
                    "companyprofile.store",
                ],
            ],
            [
                "group_name" => "customer",
                "permission" => [
                    "customer.index",
                    "customer.edit",
                    "customer.store",
                    "customer.delete",
                ],
            ],
            [
                "group_name" => "city",
                "permission" => [
                    "city.index",
                    "city.edit",
                    "city.store",
                    "city.delete",
                ],
            ],
            [
                "group_name" => "customerpayment",
                "permission" => [
                    "customerpayment.index",
                    "customerpayment.edit",
                    "customerpayment.store",
                    "customerpayment.delete",
                ],
            ],
            [
                "group_name" => "department",
                "permission" => [
                    "department.index",
                    "department.edit",
                    "department.store",
                    "department.delete",
                ],
            ],
            [
                "group_name" => "employer",
                "permission" => [
                    "employer.index",
                    "employer.edit",
                    "employer.store",
                    "employer.delete",
                ],
            ],
            [
                "group_name" => "product",
                "permission" => [
                    "product.index",
                    "product.edit",
                    "product.store",
                    "product.delete",
                ],
            ],
            [
                "group_name" => "purchase",
                "permission" => [
                    "purchase.index",
                    "purchase.edit",
                    "purchase.store",
                    "purchase.delete",
                ],
            ],
            [
                "group_name" => "supplier",
                "permission" => [
                    "supplier.index",
                    "supplier.edit",
                    "supplier.store",
                    "supplier.delete",
                ],
            ],
            [
                "group_name" => "sale",
                "permission" => [
                    "sale.index",
                    "sale.edit",
                    "sale.store",
                    "sale.delete",
                ],
            ],
            [
                "group_name" => "supplierpayment",
                "permission" => [
                    "supplierpayment.index",
                    "supplierpayment.edit",
                    "supplierpayment.store",
                    "supplierpayment.delete",
                ],
            ],
            [
                "group_name" => "unit",
                "permission" => [
                    "unit.index",
                    "unit.edit",
                    "unit.store",
                    "unit.delete",
                ],
            ],
            [
                "group_name" => "user",
                "permission" => [
                    "user.index",
                    "user.edit",
                    "user.store",
                    "user.delete",
                ],
            ],
            [
                "group_name" => "useraccess",
                "permission" => [
                    "useraccess.index",
                    "useraccess.store",
                ],
            ],
        ];


        foreach ($permissions as $permission) {
            $group_name = $permission['group_name'];
            foreach ($permission['permission'] as $perm) {
                Permission::create(["permission" => $perm, "group_name" => $group_name]);
            }
        }

        $allPermissions = Permission::all();
        foreach ($allPermissions as $key => $perm) {
            UserAccess::create([
                'user_id'    => 1,
                'group_name' => $perm->group_name,
                'permission' => $perm->permission,
            ]);
        }
    }
}
