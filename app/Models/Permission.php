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
                                array('name' => 'tenants_delete', 'description' => 'Deletar')
                            ]
                        ]
                    ]
                ],
                [
                    'name' => 'operational_view',
                    'description' => 'Operacional',
                    'children' => [
                        [
                            'name' => 'signatures_group_view',
                            'description' => 'Assinaturas',
                            'children' => [
                                array('name' => 'signatures_view', 'description' => 'Visualizar'),
                                array('name' => 'signatures_create', 'description' => 'Criar'),
                                array('name' => 'signatures_edit', 'description' => 'Editar'),
                                array('name' => 'signatures_delete', 'description' => 'Deletar')
                            ]
                        ],
                        [
                            'name' => 'due_days_group_view',
                            'description' => 'Dias de Vencimento',
                            'children' => [
                                array('name' => 'due-days_view', 'description' => 'Visualizar'),
                                array('name' => 'due-days_create', 'description' => 'Criar'),
                                array('name' => 'due-days_edit', 'description' => 'Editar'),
                                array('name' => 'due-days_delete', 'description' => 'Deletar')
                            ]
                        ]
                    ]
                ],
                [
                    'name' => 'general_view',
                    'description' => 'Geral',
                    'children' => [
                        [
                            'name' => 'payment-methods_group_view',
                            'description' => 'Métodos de Pagamento',
                            'children' => [
                                array('name' => 'payment-methods_view', 'description' => 'Visualizar'),
                                array('name' => 'payment-methods_create', 'description' => 'Criar'),
                                array('name' => 'payment-methods_edit', 'description' => 'Editar'),
                                array('name' => 'payment-methods_delete', 'description' => 'Deletar')
                            ]
                        ],
                        [
                            'name' => 'measurement-units_group_view',
                            'description' => 'Unidades de Medida',
                            'children' => [
                                array('name' => 'measurement-units_view', 'description' => 'Visualizar'),
                                array('name' => 'measurement-units_create', 'description' => 'Criar'),
                                array('name' => 'measurement-units_edit', 'description' => 'Editar'),
                                array('name' => 'measurement-units_delete', 'description' => 'Deletar')
                            ]
                        ],
                        [
                            'name' => 'ncms_group_view',
                            'description' => 'NCMS',
                            'children' => [
                                array('name' => 'ncms_view', 'description' => 'Visualizar')
                            ]
                        ],
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
                        array('name' => 'users_delete', 'description' => 'Deletar')
                    ]
                ],
                [
                    'name' => 'roles_group_view',
                    'description' => 'Atribuições/Módulos',
                    'children' => [
                        array('name' => 'roles_view', 'description' => 'Visualizar'),
                        array('name' => 'roles_create', 'description' => 'Criar'),
                        array('name' => 'roles_edit', 'description' => 'Editar'),
                        array('name' => 'roles_delete', 'description' => 'Deletar')
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
