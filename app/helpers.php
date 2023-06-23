<?php
use App\Models\Admin;
use App\Models\Role;
use App\Models\RoleMainMenu;
use App\Models\RoleSubMenu;
use App\Models\RoleSubMenuControl;
use App\Models\PermissionRoleWithMainMenu;
use App\Models\PermissionRoleWithSubMenu;
use App\Models\PermissionRoleWithSubMenuControl;

function check_main_menu_role($main_menu_name = '') {
    $com_code = auth()->user()->com_code;
    if ($main_menu_name == '' && $main_menu_name = null) {
        return false;
    }
    else {
        $main_menu_code = RoleMainMenu::where('name', $main_menu_name)->value('id');
        $admin_role_id = Admin::where('id', auth()->user()->id)->value('roles_id');

        $check = PermissionRoleWithMainMenu::where(['roles_id' => $admin_role_id, 'roles_main_menu_id' => $main_menu_code, 'com_code' => $com_code])->count();

        if ($check > 0) {
            return true;
        }
        else {
            return false;
        }
    }
}


function check_sub_menu_role($main_menu_name = '', $sub_menu_name = '') {
    $com_code = auth()->user()->com_code;
    if ($main_menu_name == '' && $main_menu_name = null || $sub_menu_name == '' && $sub_menu_name = null) {
        return false;
    }
    else {
        $main_menu_code = RoleMainMenu::where('name', $main_menu_name)->value('id');
        $sub_menu_code = RoleSubMenu::where(['name' => $sub_menu_name, 'roles_main_menu_id' => $main_menu_code])->value('id');
        $admin_role_id = Admin::where('id', auth()->user()->id)->value('roles_id');

        $check = PermissionRoleWithSubMenu::where(['roles_id' => $admin_role_id, 'roles_main_menu_id' => $main_menu_code, 'roles_sub_menu_id' => $sub_menu_code, 'com_code' => $com_code])->count();

        if ($check > 0) {
            return true;
        }
        else {
            return false;
        }
    }
}


function check_control_menu_role($main_menu_name = '', $sub_menu_name = '', $control_menu_name) {
    $com_code = auth()->user()->com_code;
    if ($main_menu_name == '' && $main_menu_name = null || $sub_menu_name == '' && $sub_menu_name = null || $control_menu_name == '' && $control_menu_name = null) {
        return false;
    }
    else {
        $main_menu_code = RoleMainMenu::where('name', $main_menu_name)->value('id');
        $sub_menu_code = RoleSubMenu::where(['name' => $sub_menu_name, 'roles_main_menu_id' => $main_menu_code])->value('id');
        $control_menu_code = RoleSubMenuControl::where(['name' => $control_menu_name, 'roles_sub_menu_id' => $sub_menu_code])->value('id');
        $admin_role_id = Admin::where('id', auth()->user()->id)->value('roles_id');

        $check = PermissionRoleWithSubMenuControl::where(['roles_id' => $admin_role_id, 'roles_main_menu_id' => $main_menu_code, 'roles_sub_menu_id' => $sub_menu_code, 'roles_sub_menu_control_id' => $control_menu_code, 'com_code' => $com_code])->count();
        if ($check > 0) {
            return true;
        }
        else {
            return false;
        }
    }
}
