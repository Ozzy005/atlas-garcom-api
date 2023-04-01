<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionsTableSeeder extends Seeder
{
    public function run(): void
    {
        try {
            $data = Permission::permissions;
            $messages = [];
            $loop = function ($data, $parent = null) use (&$loop, &$messages) {
                foreach ($data as $value) {
                    if (array_key_exists('id', $value)) {
                        $permission = Permission::query()
                            ->updateOrCreate(
                                ['id' => $value['id']],
                                [
                                    'name' => $value['name'],
                                    'description' => $value['description'],
                                    'parent_id' => $parent ? $parent->id : null,
                                ]
                            );
                        array_push($messages, "  $permission->id - Permissão {$permission->description} criada/atualizada.");
                        if (array_key_exists('children', $value)) {
                            $loop($value['children'], $permission);
                        }
                    }
                }
            };

            DB::beginTransaction();

            $loop($data);

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
