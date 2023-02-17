<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Kalnoy\Nestedset\NodeTrait;

class Permission extends \Spatie\Permission\Models\Permission
{
    use HasFactory, NodeTrait;

    const permissions = [
        [
            'name' => 'dashboard_view',
            'description' => 'Painel'
        ],
        [
            'name' => 'registrations_view',
            'description' => 'Cadastros',
            'children' => [
                [
                    'name' => 'people_view',
                    'description' => 'Pessoas',
                    'children' => [
                        [
                            'name' => 'tenants_group_view',
                            'description' => 'Contratantes',
                            'children' => [
                                array('name' => 'tenants_view', 'description' => 'Visualizar'),
                                array('name' => 'tenants_create', 'description' => 'Criar'),
                                array('name' => 'tenants_edit', 'description' => 'Editar'),
                                array('name' => 'tenants_delete', 'description' => 'Deletar'),
                            ]
                        ]
                    ]
                ],
                [
                    'name' => 'general_view',
                    'description' => 'Geral',
                    'children' => [
                        [
                            'name' => 'states_group_view',
                            'description' => 'Estados',
                            'children' => [
                                array('name' => 'states_view', 'description' => 'Visualizar')
                            ]
                        ],
                        [
                            'name' => 'cities_group_view',
                            'description' => 'Cidades',
                            'children' => [
                                array('name' => 'cities_view', 'description' => 'Visualizar')
                            ]
                        ]
                    ]
                ]
            ]
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
