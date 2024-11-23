<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AdminUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'email',
        'password',

    ];

    public static function addRoles($idAdminUser)
    {
        $modelHasRoleData = [
            'role_id' => 1,
            'model_id' => $idAdminUser,
            'model_type' => 'Brackets\AdminAuth\Models\AdminUser'
        ];

        DB::table('model_has_roles')->insert($modelHasRoleData);
    }
}
