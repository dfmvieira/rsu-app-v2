<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;

class MenusTableSeeder extends Seeder
{
    private $menuId = null;
    private $dropdownId = array();
    private $dropdown = false;
    private $sequence = 1;
    private $joinData = array();
    private $translationData = array();
    private $defaultTranslation = 'en';
    private $adminRole = null;
    private $userRole = null;

    public function join($roles, $menusId){
        $roles = explode(',', $roles);
        foreach($roles as $role){
            array_push($this->joinData, array('role_name' => $role, 'menus_id' => $menusId));
        }
    }

    /*
        Function assigns menu elements to roles
        Must by use on end of this seeder
    */
    public function joinAllByTransaction(){
        DB::beginTransaction();
        foreach($this->joinData as $data){
            DB::table('menu_role')->insert([
                'role_name' => $data['role_name'],
                'menus_id' => $data['menus_id'],
            ]);
        }
        DB::commit();
    }

    public function addTranslation($lang, $name, $menuId){
        array_push($this->translationData, array(
            'name' => $name,
            'lang' => $lang,
            'menus_id' => $menuId
        ));
    }

    /*
        Function insert All translations
        Must by use on end of this seeder
    */
    public function insertAllTranslations(){
        DB::beginTransaction();
        foreach($this->translationData as $data){
            DB::table('menus_lang')->insert([
                'name' => $data['name'],
                'lang' => $data['lang'],
                'menus_id' => $data['menus_id']
            ]);
        }
        DB::commit();
    }

    public function insertLink($roles, $name, $href, $icon = null){
        if($this->dropdown === false){
            DB::table('menus')->insert([
                'slug' => 'link',
                'icon' => $icon,
                'href' => $href,
                'menu_id' => $this->menuId,
                'sequence' => $this->sequence
            ]);
        }else{
            DB::table('menus')->insert([
                'slug' => 'link',
                'icon' => $icon,
                'href' => $href,
                'menu_id' => $this->menuId,
                'parent_id' => $this->dropdownId[count($this->dropdownId) - 1],
                'sequence' => $this->sequence
            ]);
        }
        $this->sequence++;
        $lastId = DB::getPdo()->lastInsertId();
        $this->join($roles, $lastId);
        $this->addTranslation($this->defaultTranslation, $name, $lastId);
        $permission = Permission::where('name', '=', $name)->get();
        if(empty($permission)){
            $permission = Permission::create(['name' => 'visit ' . $name]);
        }
        $roles = explode(',', $roles);
        if(in_array('user', $roles)){
            $this->userRole->givePermissionTo($permission);
        }
        if(in_array('admin', $roles)){
            $this->adminRole->givePermissionTo($permission);
        }
        return $lastId;
    }

    public function insertTitle($roles, $name){
        DB::table('menus')->insert([
            'slug' => 'title',
            'menu_id' => $this->menuId,
            'sequence' => $this->sequence
        ]);
        $this->sequence++;
        $lastId = DB::getPdo()->lastInsertId();
        $this->join($roles, $lastId);
        $this->addTranslation($this->defaultTranslation, $name, $lastId);
        return $lastId;
    }

    public function beginDropdown($roles, $name, $href='', $icon=''){
        if(count($this->dropdownId)){
            $parentId = $this->dropdownId[count($this->dropdownId) - 1];
        }else{
            $parentId = null;
        }
        DB::table('menus')->insert([
            'slug' => 'dropdown',
            'icon' => $icon,
            'menu_id' => $this->menuId,
            'sequence' => $this->sequence,
            'parent_id' => $parentId,
            'href' => $href,
        ]);
        $lastId = DB::getPdo()->lastInsertId();
        array_push($this->dropdownId, $lastId);
        $this->dropdown = true;
        $this->sequence++;
        $this->join($roles, $lastId);
        $this->addTranslation($this->defaultTranslation, $name, $lastId);
        return $lastId;
    }

    public function endDropdown(){
        $this->dropdown = false;
        array_pop( $this->dropdownId );
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /* Get roles */
        $this->adminRole = Role::where('name' , '=' , 'admin' )->first();
        if(empty($this->adminRole)){
            $this->adminRole = Role::create(['name' => 'admin']);
        }
        $this->userRole = Role::where('name' , '=' , 'user' )->first();
        if(empty($this->userRole)){
            $this->userRole = Role::create(['name' => 'user']);
        }
        /* Create Translation languages */
        DB::table('menu_lang_lists')->insert([
            'name' => 'English',
            'short_name' => 'en',
            'is_default' => true
        ]);
        /* DB::table('menu_lang_lists')->insert([
            'name' => 'Polish',
            'short_name' => 'pl'
        ]); */
        
        /* Create Sidebar menu */
        DB::table('menulist')->insert([
            'name' => 'sidebar menu'
        ]);
        $this->menuId = DB::getPdo()->lastInsertId();  //set menuId
        
        $id = $this->insertLink('guest,user,admin', 'Dashboard', '/', 'cil-speedometer');
        $id = $this->insertLink('guest', 'Login', '/login', 'cil-account-logout');
        $id = $this->insertLink('guest', 'Register', '/register', 'cil-account-logout');

        
        $this->beginDropdown('user,admin', 'Ivi Sign Map', '/ivisignmap', 'cil-map');
            $this->insertLink('user,admin', 'View', '/ivisignmap');
            $this->insertLink('user,admin', 'Add', '/ivisignmap/add');
        $this->endDropdown();

        $this->beginDropdown('user,admin', 'Ivi Messages', '/ivimessages', 'cil-envelope-open');
            $this->insertLink('user,admin', 'View Ivi Messages', '/ivimessages');
            $this->insertLink('user,admin', 'Create Ivi Message', '/ivimessages/create');
        $this->endDropdown();
        
        $this->insertLink('user,admin', 'Published Signs', '/publishedsigns', 'cil-newspaper');

        $this->beginDropdown('user,admin', 'Deploy Groups', '/deploygroups', 'cil-people');
            $this->insertLink('user,admin', 'Deploy Groups', '/deploygroyps');
        $this->endDropdown();

        $this->beginDropdown('user,admin', 'Master Data', '#', 'cil-settings');
        $this->beginDropdown('user,admin', 'Vienna Signs', '/vienna', 'cil-circle');
                $this->insertLink('user,admin', 'View', '/vienna');
                $this->insertLink('user,admin', 'Add', '/vienna/add');
                $this->insertLink('user,admin', 'Signs Categories', '/signsCategories');
                $this->insertLink('user,admin', 'Add Sign Category', '/signsCategories/add');
            $this->endDropdown();
            $this->beginDropdown('admin', 'Users', '#', 'cil-people');
                $this->insertLink('admin', 'View All', '/users');
                $this->insertLink('user', 'View User', '/users/view');
                $this->insertLink('admin', 'Add User', 'users/add');
            $this->endDropdown();
            $this->beginDropdown('admin', 'Roles', '#', 'cil-https');
                $this->insertLink('admin', 'View All', '/roles');
                $this->insertLink('user', 'View Role', '/roles/view');
                $this->insertLink('admin', 'Add Role', '/roles/add');
            $this->endDropdown();
            $this->beginDropdown('admin', 'Entities', '#', 'cil-building');
                $this->insertLink('admin', 'View All', '/entities');
                $this->insertLink('admin,user', 'View Entity', '/entities/view');
                $this->insertLink('admin', 'Add Entity', 'entities/add');
            $this->endDropdown();
        $this->endDropdown();

        $id = $this->beginDropdown('admin', 'Settings', '#', 'cil-puzzle');
            $id = $this->insertLink('admin', 'Media',    '/media');
            $id = $this->insertLink('admin', 'Users',    '/users');
            $id = $this->insertLink('admin', 'Menu',    '/menu');
            $id = $this->insertLink('admin', 'BREAD',    '/bread');
            $id = $this->insertLink('admin', 'Email',    '/email');
        $this->endDropdown();


        
        /* Create top menu */
        DB::table('menulist')->insert([
            'name' => 'top_menu'
        ]);
        $this->menuId = DB::getPdo()->lastInsertId();  //set menuId
       

        $this->joinAllByTransaction();   ///   <===== Must by use on end of this seeder
        $this->insertAllTranslations();  ///   <===== Must by use on end of this seeder
    }
}
