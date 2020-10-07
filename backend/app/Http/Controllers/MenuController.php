<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index()
    {
        return [
            [
                'label' => 'Beranda',
                'path' => '/',
                'icon' => 'el-icon-pie-chart'
            ],
            [
                'label' => 'Order',
                'path' => '/order',
                'icon' => 'el-icon-s-order'
            ],
            [
                'label' => 'Sarana',
                'path' => '/sarana',
                'icon' => 'el-icon-truck'
            ],
            [
                'label' => 'Jenis Sarana',
                'path' => '/jenis-sarana',
                'icon' => 'el-icon-collection-tag'
            ],
            // [
            //     'label' => 'Bogie',
            //     'path' => '/bogie',
            //     'icon' => 'el-icon-truck'
            // ],
            // [
            //     'label' => 'Dipo',
            //     'path' => '/dipo',
            //     'icon' => 'el-icon-school'
            // ],
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
                'icon' => 'el-icon-s-tools'
            ],
            // [
            //     'label' => 'Jenis Detail Pekerjaan',
            //     'path' => '/jenis-detail-pekerjaan',
            //     'icon' => 'el-icon-s-operation'
            // ],
            [
                'label' => 'Program Kerja',
                'path' => '/program-kerja',
                'icon' => 'el-icon-date'
            ],
            [
                'label' => 'User',
                'path' => '/user',
                'icon' => 'el-icon-user'
            ],
        ];
    }
}
