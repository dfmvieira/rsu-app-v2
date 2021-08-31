<?php
/*
*   07.11.2019
*   MenusMenu.php
*/
namespace App\Http\Menus;

use App\MenuBuilder\MenuBuilder;
use Illuminate\Support\Facades\DB;
use App\Models\Menus;
use App\MenuBuilder\RenderFromDatabaseData;

class GetSidebarMenu implements MenuInterface{

    private $mb; //menu builder
    private $menu;

    public function __construct(){
        $this->mb = new MenuBuilder();
    }

    private function getMenuFromDB($menuName, $role, $locale){
        $this->menu = Menus::join('menu_role', 'menus.id', '=', 'menu_role.menus_id')
            ->join('menus_lang', 'menus.id', '=', 'menus_lang.menus_id')
            ->join('menulist', 'menulist.id', '=', 'menus.menu_id')
            ->select('menus.*', 'menus_lang.name as name')
            ->where('menulist.name', '=', $menuName)
            ->where('menu_role.role_name', '=', $role)
            ->where('menus_lang.lang', '=', $locale)
            ->orderBy('menus.sequence', 'asc')->get();       
    }

    private function getGuestMenu($locale, $menuName){
        $this->getMenuFromDB($menuName, 'guest', $locale);
    }

    private function getUserMenu($locale, $menuName){
        $this->getMenuFromDB($menuName, 'user', $locale);
    }

    private function getAdminMenu($locale, $menuName){
        $this->getMenuFromDB($menuName, 'admin', $locale);
    }

    private function getRoleMenu($locale, $role, $menuName) {
        $this->getMenuFromDB($menuName, $role, $locale);
    }

    public function get($roles, $locale, $menuName = 'sidebar menu'){
        $roles = explode(',', $roles);
        if(empty($roles)){
            $this->getGuestMenu($locale, $menuName);
        }elseif(in_array('admin', $roles)){
            $this->getAdminMenu($locale, $menuName);
        }elseif(in_array('user', $roles)){
            $this->getUserMenu($locale, $menuName);
        }elseif(in_array('entityadmin', $roles)){
            $this->getRoleMenu($locale, 'entityadmin', $menuName);
        }elseif(in_array('planner', $roles)){
            $this->getRoleMenu($locale, 'planner', $menuName);
        }elseif(in_array('factory', $roles)){
            $this->getRoleMenu($locale, 'factory', $menuName);
        }elseif(in_array('maintenanceteam', $roles)){
            $this->getRoleMenu($locale, 'maintenanceteam', $menuName);
        }elseif(in_array('monitor', $roles)){
            $this->getRoleMenu($locale, 'monitor', $menuName);
        }elseif(in_array('deploymanager', $roles)){
            $this->getRoleMenu($locale, 'deploymanager', $menuName);
        }elseif(in_array('technician', $roles)){
            $this->getRoleMenu($locale, 'technician', $menuName);
        }else {
            $this->getGuestMenu($locale, $menuName);
        }
        $rfd = new RenderFromDatabaseData;
        return $rfd->render($this->menu);
    }

    public function getAll($locale, $menuId = 1 ){
        $this->menu = Menus::join('menus_lang', 'menus.id', '=', 'menus_lang.menus_id')
            ->select('menus.*', 'menus_lang.name as name')
            ->where('menus.menu_id', '=', $menuId)
            ->where('menus_lang.lang', '=', $locale)
            ->orderBy('menus.sequence', 'asc')->get();   
        $rfd = new RenderFromDatabaseData;
        return $rfd->render($this->menu);
    }
}