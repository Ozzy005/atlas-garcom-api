<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesTableSync extends Seeder
{
    public function run(): void
    {
        try {
            $messages = [];

            DB::beginTransaction();

            $roles = Role::query()->with('permissions')->get();

            foreach ($roles as $role) {
                $roleArray = $role->permissions->pluck('name')->toArray();
                $role->syncPermissions($roleArray);
                array_push($messages, "  $role->id - Role {$role->description} sincronizada.");
            }

            DB::commit();

            foreach ($messages as $message) {
                $this->command->info($message);
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->command->alert($th->getMessage());
        }
    }
}
