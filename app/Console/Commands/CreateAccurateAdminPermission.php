<?php

namespace App\Console\Commands;

use App\Domains\Auth\Models\Permission;
use App\Domains\Auth\Models\User;
use Illuminate\Console\Command;

class CreateAccurateAdminPermission extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-accurate-admin-permission';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $permission_accurate = Permission::create([
            'type' => User::TYPE_ADMIN,
            'name' => 'admin.access.accurate',
            'description' => 'All Accurate Permissions',
        ]);
    }
}
