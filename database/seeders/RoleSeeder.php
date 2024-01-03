<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role1 = Role::create(['name' => 'Admin']);
        $role2 = Role::create(['name' => 'User']);

        Permission::create(['name' => 'options.index', 'description' => 'Options Module'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'users.index', 'description' => 'User Module'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'users.create', 'description' => 'Create Users'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'users.edit', 'description' => 'Edit Users'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'users.destroy', 'description' => 'Delete Users'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'users.view', 'description' => 'View Users'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'roles.index', 'description' => 'Role Module'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'roles.create', 'description' => 'Create Role'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'roles.edit', 'description' => 'Edit Role'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'roles.destroy', 'description' => 'Delete Role'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'roles.show', 'description' => 'Show Role'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'color.index', 'description' => 'Color Module'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'color.create', 'description' => 'Create Role'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'color.edit', 'description' => 'Edit Role'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'color.destroy', 'description' => 'Delete Role'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'delivery.index', 'description' => 'Delivery Module'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'delivery.create', 'description' => 'Create Delivery'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'delivery.edit', 'description' => 'Edit Delivery'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'delivery.destroy', 'description' => 'Delete Delivery'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'document.index', 'description' => 'Document Number Module'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'document.edit', 'description' => 'Edit Document Number'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'email.index', 'description' => 'Email Module'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'group.index', 'description' => 'Group Module'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'group.create', 'description' => 'Create Group'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'group.edit', 'description' => 'Edit Group'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'group.destroy', 'description' => 'Delete Group'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'unit.index', 'description' => 'Unit Measure Module'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'unit.create', 'description' => 'Create Unit Measure'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'unit.edit', 'description' => 'Edit Unit Measure'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'unit.destroy', 'description' => 'Delete Unit Measure'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'size.index', 'description' => 'Size Module'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'size.create', 'description' => 'Create Size'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'size.edit', 'description' => 'Edit Size'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'size.destroy', 'description' => 'Delete Size'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'taxes.index', 'description' => 'Taxes Module'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'taxes.create', 'description' => 'Create Taxes'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'taxes.edit', 'description' => 'Edit Taxes'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'taxes.destroy', 'description' => 'Delete Taxes'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'terms.index', 'description' => 'Terms Module'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'terms.create', 'description' => 'Create Terms'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'terms.edit', 'description' => 'Edit Terms'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'terms.destroy', 'description' => 'Delete Terms'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'estimate.index', 'description' => 'Estimate Module'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'estimate.create', 'description' => 'Create Estimate'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'estimate.edit', 'description' => 'Edit Estimate'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'estimate.destroy', 'description' => 'Delete Estimate'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'estimate.show', 'description' => 'Show Estimate'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'estimate.send', 'description' => 'Send Estimate'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'invoice.index', 'description' => 'Invoice Module'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'invoice.create', 'description' => 'Create Invoice'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'invoice.edit', 'description' => 'Edit Invoice'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'invoice.destroy', 'description' => 'Delete Invoice'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'invoice.show', 'description' => 'Show Invoice'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'invoice.send', 'description' => 'Send Invoice'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'bill.index', 'description' => 'Bill Module'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'bill.create', 'description' => 'Create Bill'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'bill.edit', 'description' => 'Edit Bill'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'bill.destroy', 'description' => 'Delete Bill'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'bill.show', 'description' => 'Show Bill'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'customer.index', 'description' => 'Customer Module'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'customer.create', 'description' => 'Create Customer'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'customer.edit', 'description' => 'Edit Customer'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'customer.show', 'description' => 'Show Customer'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'customer.destroy', 'description' => 'Delete Customer'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'vendor.index', 'description' => 'Vendor Module'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'vendor.create', 'description' => 'Create Vendor'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'vendor.edit', 'description' => 'Edit Vendor'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'vendor.destroy', 'description' => 'Delete Vendor'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'inventory.index', 'description' => 'Inventory Module'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'inventory.create', 'description' => 'Create Inventory'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'inventory.edit', 'description' => 'Edit Inventory'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'inventory.destroy', 'description' => 'Delete Inventory'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'process.index', 'description' => 'Process Module'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'process.create', 'description' => 'Create Process'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'process.edit', 'description' => 'Edit Process'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'process.destroy', 'description' => 'Delete Process'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'payment.index', 'description' => 'Payment Module'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'payment.create', 'description' => 'Create Payment'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'payment.edit', 'description' => 'Edit Payment'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'payment.show', 'description' => 'Show Payment'])->syncRoles([$role1, $role2]);      
        Permission::create(['name' => 'payment.destroy', 'description' => 'Delete Payment'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'control.index', 'description' => 'Control Module'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'control.show', 'description' => 'Show Control'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'control.approve', 'description' => 'Approve Control'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'report.index', 'description' => 'Report Module'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'print.index', 'description' => 'Print Barcode'])->syncRoles([$role1, $role2]);

    }   
}
