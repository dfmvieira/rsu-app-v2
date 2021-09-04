<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use App\Models\User;
use App\Models\RoleHierarchy;

class UsersAndNotesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $numberOfUsers = 10;
        $numberOfNotes = 5000;
        $usersIds = array();
        $statusIds = array();
        $userStatus = array(
            'Active',
            'Inactive',
            'Pending',
            'Banned'
        );
        /* Create roles */
        $adminRole = Role::where('name' , '=' , 'admin' )->first();
        if(empty($adminRole)){
            $adminRole = Role::create(['name' => 'admin']);
        }
        RoleHierarchy::create([
            'role_id' => $adminRole->id,
            'hierarchy' => 1,
        ]);
        $entityadminRole = Role::where('name' , '=' , 'entityadmin' )->first();
        if(empty($entityadminRole)){
            $entityadminRole = Role::create(['name' => 'entityadmin']);
        }
        RoleHierarchy::create([
            'role_id' => $entityadminRole->id,
            'hierarchy' => 2,
        ]);
        $plannerRole = Role::where('name' , '=' , 'planner' )->first();
        if(empty($plannerRole)){
            $plannerRole = Role::create(['name' => 'planner']);
        }
        RoleHierarchy::create([
            'role_id' => $plannerRole->id,
            'hierarchy' => 3,
        ]);
        $factoryRole = Role::where('name' , '=' , 'factory' )->first();
        if(empty($factoryRole)){
            $factoryRole = Role::create(['name' => 'factory']);
        }
        RoleHierarchy::create([
            'role_id' => $factoryRole->id,
            'hierarchy' => 4,
        ]);
        $maintenanceteamRole = Role::where('name' , '=' , 'maintenanceteam' )->first();
        if(empty($maintenanceteamRole)){
            $maintenanceteamRole = Role::create(['name' => 'maintenanceteam']);
        }
        RoleHierarchy::create([
            'role_id' => $maintenanceteamRole->id,
            'hierarchy' => 5,
        ]);
        $monitorRole = Role::where('name' , '=' , 'monitor' )->first();
        if(empty($monitorRole)){
            $monitorRole = Role::create(['name' => 'monitor']);
        }
        RoleHierarchy::create([
            'role_id' => $monitorRole->id,
            'hierarchy' => 6,
        ]);
        $deploymanagerRole = Role::where('name' , '=' , 'deploymanager' )->first();
        if(empty($deploymanagerRole)){
            $deploymanagerRole = Role::create(['name' => 'deploymanager']);
        }
        RoleHierarchy::create([
            'role_id' => $deploymanagerRole->id,
            'hierarchy' => 7,
        ]);
        $technicianRole = Role::where('name' , '=' , 'technician' )->first();
        if(empty($technicianRole)){
            $technicianRole = Role::create(['name' => 'technician']);
        }
        RoleHierarchy::create([
            'role_id' => $technicianRole->id,
            'hierarchy' => 8,
        ]);



        $faker = Faker::create();
        /*  insert status  */
        DB::table('status')->insert([
            'name' => 'ongoing',
            'class' => 'primary',
        ]);
        array_push($statusIds, DB::getPdo()->lastInsertId());
        DB::table('status')->insert([
            'name' => 'stopped',
            'class' => 'secondary',
        ]);
        array_push($statusIds, DB::getPdo()->lastInsertId());
        DB::table('status')->insert([
            'name' => 'completed',
            'class' => 'success',
        ]);
        array_push($statusIds, DB::getPdo()->lastInsertId());
        DB::table('status')->insert([
            'name' => 'expired',
            'class' => 'warning',
        ]);
        array_push($statusIds, DB::getPdo()->lastInsertId());
        /*  insert users   */
        $user = User::create([
            'name' => 'admin',
            'phone' => '911111111',
            'email' => 'admin@admin.com',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
            'menuroles' => 'admin,deploymanager,entityadmin,factory,maintenanceteam,monitor,planner,technician',
            'IDEntity' => 1,
            'status' => 'Active'
        ]);
        $user->assignRole('admin');
        $user->assignRole('deploymanager');
        $user->assignRole('entityadmin');
        $user->assignRole('factory');
        $user->assignRole('maintenanceteam');
        $user->assignRole('monitor');
        $user->assignRole('planner');
        $user->assignRole('technician');

        $user = User::create([
            'name' => 'entityadmin',
            'phone' => '911111111',
            'email' => 'entityadmin@mail.com',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
            'menuroles' => 'entityadmin',
            'IDEntity' => 1,
            'status' => 'Active'
        ]);
        $user->assignRole('entityadmin');
        
        $user = User::create([
            'name' => 'planner',
            'phone' => '911111111',
            'email' => 'planner@mail.com',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
            'menuroles' => 'planner',
            'IDEntity' => 1,
            'status' => 'Active'
        ]);
        $user->assignRole('planner');

        $user = User::create([
            'name' => 'factory',
            'phone' => '911111111',
            'email' => 'factory@mail.com',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
            'menuroles' => 'factory',
            'IDEntity' => 1,
            'status' => 'Active'
        ]);
        $user->assignRole('factory');
        
        $user = User::create([
            'name' => 'maintenanceTeam',
            'phone' => '911111111',
            'email' => 'maintenanceteam@mail.com',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
            'menuroles' => 'maintenanceTeam',
            'IDEntity' => 1,
            'status' => 'Active'
        ]);
        $user->assignRole('maintenanceTeam');

        $user = User::create([
            'name' => 'monitor',
            'phone' => '911111111',
            'email' => 'monitor@mail.com',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
            'menuroles' => 'monitor',
            'IDEntity' => 1,
            'status' => 'Active'
        ]);
        $user->assignRole('monitor');

        $user = User::create([
            'name' => 'deploymanager',
            'phone' => '911111111',
            'email' => 'deploymanager@mail.com',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
            'menuroles' => 'deploymanager',
            'IDEntity' => 1,
            'status' => 'Active'
        ]);
        $user->assignRole('deploymanager');

        $user = User::create([
            'name' => 'worker1',
            'phone' => '911111111',
            'email' => 'worker1@mail.com',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
            'menuroles' => 'technician',
            'IDEntity' => 1,
            'status' => 'Active'
        ]);
        $user->assignRole('technician');

        $user = User::create([
            'name' => 'worker2',
            'phone' => '911111111',
            'email' => 'worker2@mail.com',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
            'menuroles' => 'technician',
            'IDEntity' => 1,
            'status' => 'Active'
        ]);
        $user->assignRole('technician');

        $user = User::create([
            'name' => 'worker3',
            'phone' => '911111111',
            'email' => 'worker3@mail.com',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
            'menuroles' => 'technician',
            'IDEntity' => 1,
            'status' => 'Active'
        ]);
        $user->assignRole('technician');

        $user = User::create([
            'name' => 'worker4',
            'phone' => '911111111',
            'email' => 'worker4@mail.com',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
            'menuroles' => 'technician',
            'IDEntity' => 1,
            'status' => 'Active'
        ]);
        $user->assignRole('technician');


        /* for($i = 0; $i<$numberOfUsers; $i++){
            $user = User::create([
                'name' => $faker->name(),
                'email' => $faker->unique()->safeEmail(),
                'email_verified_at' => now(),
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                'remember_token' => Str::random(10),
                'menuroles' => 'planer',
                'IDEntity' => 2,
                'status' => $userStatus[ random_int(0,count($userStatus) - 1) ]
            ]);
            $user->assignRole('planner');
            array_push($usersIds, $user->id);
        } */
        /*  insert notes  */
        /* DB::beginTransaction();
        for($i = 0; $i<$numberOfNotes; $i++){
            $noteType = $faker->word();
            if(random_int(0,1)){
                $noteType .= ' ' . $faker->word();
            }
            DB::table('notes')->insert([
                'title'         => $faker->sentence(4,true),
                'content'       => $faker->paragraph(3,true),
                'status_id'     => $statusIds[random_int(0,count($statusIds) - 1)],
                'note_type'     => $noteType,
                'applies_to_date' => $faker->date(),
                'users_id'      => $usersIds[random_int(0,$numberOfUsers-1)]
            ]);
        } */
        DB::commit();
    }
}