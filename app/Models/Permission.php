<?php

namespace App\Models;

use Kalnoy\Nestedset\NodeTrait;

class Permission extends \Spatie\Permission\Models\Permission
{
    use NodeTrait;

    const permissions = [
        [
            'name' => 'home',
            'description' => 'Painel'
        ],
        [
            'name' => 'management_group_view',
            'description' => 'Gerenciamento',
            'children' => [
                [
                    'name' => 'users_group_view',
                    'description' => 'Usuários',
                    'children' => [
                        array('name' => 'users_view', 'description' => 'Visualizar'),
                        array('name' => 'users_create', 'description' => 'Criar'),
                        array('name' => 'users_edit', 'description' => 'Editar'),
                        array('name' => 'users_delete', 'description' => 'Deletar'),
                    ]
                ],
                [
                    'name' => 'roles_group_view',
                    'description' => 'Atribuições',
                    'children' => [
                        array('name' => 'roles_view', 'description' => 'Visualizar'),
                        array('name' => 'roles_create', 'description' => 'Criar'),
                        array('name' => 'roles_edit', 'description' => 'Editar'),
                        array('name' => 'roles_delete', 'description' => 'Deletar'),
                    ]
                ],
                [
                    'name' => 'permissions_group_view',
                    'description' => 'Permissões',
                    'children' => [
                        array('name' => 'permissions_view', 'description' => 'Visualizar'),
                        array('name' => 'permissions_edit', 'description' => 'Editar')
                    ]
                ]
            ]
        ]
    ];
}
