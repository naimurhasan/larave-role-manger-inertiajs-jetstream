<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class CreatePermissionTables extends Migration
{
   
    
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tableNames = config('permission.table_names');
        $columnNames = config('permission.column_names');

        if (empty($tableNames)) {
            throw new \Exception('Error: config/permission.php not loaded. Run [php artisan config:clear] and try again.');
        }

        Schema::create($tableNames['permissions'], function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('guard_name');
            $table->timestamps();
        });

        Schema::create($tableNames['roles'], function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('guard_name');
            $table->timestamps();
        });

        Schema::create($tableNames['model_has_permissions'], function (Blueprint $table) use ($tableNames, $columnNames) {
            $table->unsignedBigInteger('permission_id');

            $table->string('model_type');
            $table->unsignedBigInteger($columnNames['model_morph_key']);
            $table->index([$columnNames['model_morph_key'], 'model_type'], 'model_has_permissions_model_id_model_type_index');

            $table->foreign('permission_id')
                ->references('id')
                ->on($tableNames['permissions'])
                ->onDelete('cascade');

            $table->primary(['permission_id', $columnNames['model_morph_key'], 'model_type'],
                    'model_has_permissions_permission_model_type_primary');
        });

        Schema::create($tableNames['model_has_roles'], function (Blueprint $table) use ($tableNames, $columnNames) {
            $table->unsignedBigInteger('role_id');

            $table->string('model_type');
            $table->unsignedBigInteger($columnNames['model_morph_key']);
            $table->index([$columnNames['model_morph_key'], 'model_type'], 'model_has_roles_model_id_model_type_index');

            $table->foreign('role_id')
                ->references('id')
                ->on($tableNames['roles'])
                ->onDelete('cascade');

            $table->primary(['role_id', $columnNames['model_morph_key'], 'model_type'],
                    'model_has_roles_role_model_type_primary');
        });

        Schema::create($tableNames['role_has_permissions'], function (Blueprint $table) use ($tableNames) {
            $table->unsignedBigInteger('permission_id');
            $table->unsignedBigInteger('role_id');

            $table->foreign('permission_id')
                ->references('id')
                ->on($tableNames['permissions'])
                ->onDelete('cascade');

            $table->foreign('role_id')
                ->references('id')
                ->on($tableNames['roles'])
                ->onDelete('cascade');

            $table->primary(['permission_id', 'role_id'], 'role_has_permissions_permission_id_role_id_primary');
        });

        app('cache')
            ->store(config('permission.cache.store') != 'default' ? config('permission.cache.store') : null)
            ->forget(config('permission.cache.key'));


        $this->seedRoleAndPermissions();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $tableNames = config('permission.table_names');

        if (empty($tableNames)) {
            throw new \Exception('Error: config/permission.php not found and defaults could not be merged. Please publish the package configuration before proceeding, or drop the tables manually.');
        }

        Schema::drop($tableNames['role_has_permissions']);
        Schema::drop($tableNames['model_has_roles']);
        Schema::drop($tableNames['model_has_permissions']);
        Schema::drop($tableNames['roles']);
        Schema::drop($tableNames['permissions']);
    }

    public $roles = ['Super Admin', 'Admin', 'Field Agent', 'Member'];

    // Please vaoid naming General Create | Edit | View | Delete
    public $permissions = [

        'View Dashboard Default',
        
        'Role View',
        'Role Create',
        'Role Edit',
        'Role Delete',

        'Admin Create',
        
        'Staff View',
        'Staff Create',
        'Staff Edit',
        'Staff Delete',

        'Member View',
        'Member Create',
        'Member Edit',
        'Member Delete'

    ];

    function seedRoleAndPermissions(){
        $this->insertRoles();
        $this->insertPermissions();

        $this->syncSuperAdminPermissions();
        $this->makeASuperAdmin();

        $this->syncAdminPermissions();
        $this->makeADemoAdmin();

    }

    function insertRoles(){
        foreach($this->roles as $role){
            Role::create(['name' => $role, 'guard_name'=> 'sanctum']);
        }
    }

    function insertPermissions(){
        foreach($this->permissions as $permission){
            Permission::create(['name' => $permission, 'guard_name' => 'sanctum']);
        }
    }

    function syncSuperAdminPermissions(){
       $superAdmin = Role::findByName('Super Admin',  $guardName = 'sanctum');
       $superAdmin->syncPermissions(['Role Create', 'Role Edit', 'Admin Create', 'Role View', 'Role Delete']);
    }

    function syncAdminPermissions(){
        $superAdmin = Role::findByName('Admin', $guardName='sanctum');
        $superAdmin->syncPermissions([
            'View Dashboard Default',
            
            'Staff View',
            'Staff Create',
            'Staff Edit',
            'Staff Delete',

            'Member View',
            'Member Create',
            'Member Edit',
            'Member Delete'
        ]);
     }

    function makeASuperAdmin(){
        
        $user = User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@demo.com',
            'password' => Hash::make('demo'),
        ]);

        $user->syncRoles(['Super Admin']);

    }
    
    function makeADemoAdmin(){
        $user = User::create([
            'name' => 'Demo Admin',
            'email' => 'admin@demo.com',
            'password' => Hash::make('demo'),
        ]);

        $user->assignrole('Admin');
    }

    
}
