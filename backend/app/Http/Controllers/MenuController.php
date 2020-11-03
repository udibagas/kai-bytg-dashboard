<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index(Request $request)
    {
        $menus = [
            [
                'label' => 'Beranda',
                'path' => '/',
                'icon' => 'el-icon-pie-chart',
                'roles' => [0, 10, 20, 30]
            ],
            [
                'label' => 'Order',
                'path' => '/order',
                'icon' => 'el-icon-s-order',
                'roles' => [10, 20, 30]
            ],
            [
                'label' => 'Sarana',
                'path' => '/sarana',
                'icon' => 'el-icon-truck',
                'roles' => [10, 20, 30]
            ],
            [
                'label' => 'Jenis Sarana',
                'path' => '/jenis-sarana',
                'icon' => 'el-icon-collection-tag',
                'roles' => [10, 20, 30]
            ],
            // [
            //     'label' => 'Bogie',
            //     'path' => '/bogie',
            //     'icon' => 'el-icon-truck'
            // ],
            [
                'label' => 'Dipo',
                'path' => '/dipo',
                'icon' => 'el-icon-school',
                'roles' => [10, 20, 30]
            ],
            // [
            //     'label' => 'Stasiun',
            //     'path' => '/stasiun',
            //     'icon' => 'el-icon-location-outline'
            // ],
            // [
            //     'label' => 'Jalur',
            //     'path' => '/jalur',
            //     'icon' => 'el-icon-guide'
            // ],
            [
                'label' => 'Jenis Pekerjaan',
                'path' => '/jenis-pekerjaan',
                'icon' => 'el-icon-s-tools',
                'roles' => [10, 20, 30]
            ],
            [
                'label' => 'Item Pekerjaan',
                'path' => '/item-pekerjaan',
                'icon' => 'el-icon-s-operation',
                'roles' => [30]
            ],
            [
                'label' => 'Program Kerja',
                'path' => '/program-kerja',
                'icon' => 'el-icon-date',
                'roles' => [10, 30]
            ],
            [
                'label' => 'User',
                'path' => '/user',
                'icon' => 'el-icon-user',
                'roles' => [30]
            ],
        ];

        return array_filter($menus, function ($item) use ($request) {
            return in_array($request->user()->role, $item['roles']);
        });
    }
}
