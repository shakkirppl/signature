<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Role::create(['name' => 'admin']);
        // Role::create(['name' => 'Production manager']);
        // Role::create(['name' => 'Accountant']);
        // Role::create(['name' => 'Procurement']);
        // Role::create(['name' => 'Facility Manager']);
        // Role::create(['name' => 'Lairage Incharge']);
        // Role::create(['name' => 'Meat Inspector']);
        // Role::create(['name' => 'Weight Recorde']);
        // Role::create(['name' => 'Quality Control Manager']);
        // Role::create(['name' => 'Offal Incharge']);
        // Role::create(['name' => 'Security Officer']);
    
        // // Assigning Roles to Users
        // $admin = User::find(1);
        // $admin->assignRole('admin');
    
        // $productManager = User::find(2);
        // $productManager->assignRole('Production manager');

        // $accountant = User::find(3);
        // $accountant->assignRole('Accountant');

        
        // $procurement = User::find(4);
        // $procurement->assignRole('Procurement');
          
        // $facilityManager = User::find(5);
        // $facilityManager->assignRole('Facility Manager');

        // $lairageIncharge = User::find(6);
        // $lairageIncharge->assignRole('Lairage Incharge');

        // $meatInspector = User::find(7);
        // $meatInspector->assignRole('Meat Inspector');

        // $weightRecorde = User::find(8);
        // $weightRecorde->assignRole('Weight Recorde');

        // $qualityManager = User::find(9);
        // $qualityManager->assignRole('Quality Control Manager');

        // $offalIncharge = User::find(10);
        // $offalIncharge->assignRole('Offal Incharge');

        // $securityOfficer = User::find(11);
        // $securityOfficer->assignRole('Security Officer');
        $users = User::all();

        foreach ($users as $user) {
            // Ensure the user has a valid designation relationship
            if ($user->designation) {
                $roleName = $user->designation->designation; // Fetch the designation name from the related table
                
                // Check if the role exists, then assign it
                if (!Role::where('name', $roleName)->exists()) {
                    Role::create(['name' => $roleName]); // Create role if it doesn't exist
                }
        
                if (!$user->hasRole($roleName)) {
                    $user->assignRole($roleName); // Assign role
                }
            }
        }
}

}
