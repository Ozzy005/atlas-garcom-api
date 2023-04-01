<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Kalnoy\Nestedset\NodeTrait;

/**
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $guard_name
 * @property integer $_lft
 * @property integer $_rgt
 * @property integer $parent_id
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */

class Permission extends \Spatie\Permission\Models\Permission
{
    use HasFactory, NodeTrait;

    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'description' => 'string',
        'guard_name' => 'string',
        '_lft' => 'integer',
        '_rgt' => 'integer',
        'parent_id' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    const permissions = [
        [
            'id' => 1,
            'name' => 'dashboard_view',
            'description' => 'Painel'
        ],
        [
            'id' => 2,
            'name' => 'people_view',
            'description' => 'Pessoas',
            'children' => [
                [
                    'id' => 3,
                    'name' => 'tenants_group_view',
                    'description' => 'Contratantes',
                    'children' => [
                        array('id' => 4, 'name' => 'tenants_create', 'description' => 'Criar'),
                        array('id' => 5, 'name' => 'tenants_view', 'description' => 'Visualizar'),
                        array('id' => 6, 'name' => 'tenants_edit', 'description' => 'Editar'),
                        array('id' => 7, 'name' => 'tenants_delete', 'description' => 'Deletar')
                    ]
                ]
            ]
        ],
        [
            'id' => 8,
            'name' => 'operational_view',
            'description' => 'Operacional',
            'children' => [
                [
                    'id' => 9,
                    'name' => 'signatures_group_view',
                    'description' => 'Assinaturas',
                    'children' => [
                        array('id' => 10, 'name' => 'signatures_create', 'description' => 'Criar'),
                        array('id' => 11, 'name' => 'signatures_view', 'description' => 'Visualizar'),
                        array('id' => 12, 'name' => 'signatures_edit', 'description' => 'Editar'),
                        array('id' => 13, 'name' => 'signatures_delete', 'description' => 'Deletar')
                    ]
                ],
                [
                    'id' => 14,
                    'name' => 'due_days_group_view',
                    'description' => 'Dias de Vencimento',
                    'children' => [
                        array('id' => 15, 'name' => 'due-days_create', 'description' => 'Criar'),
                        array('id' => 16, 'name' => 'due-days_view', 'description' => 'Visualizar'),
                        array('id' => 17, 'name' => 'due-days_edit', 'description' => 'Editar'),
                        array('id' => 18, 'name' => 'due-days_delete', 'description' => 'Deletar')
                    ]
                ]
            ]
        ],
        [
            'id' => 19,
            'name' => 'general_view',
            'description' => 'Geral',
            'children' => [
                [
                    'id' => 20,
                    'name' => 'payment-methods_group_view',
                    'description' => 'Métodos de Pagamento',
                    'children' => [
                        array('id' => 21, 'name' => 'payment-methods_create', 'description' => 'Criar'),
                        array('id' => 22, 'name' => 'payment-methods_view', 'description' => 'Visualizar'),
                        array('id' => 23, 'name' => 'payment-methods_edit', 'description' => 'Editar'),
                        array('id' => 24, 'name' => 'payment-methods_delete', 'description' => 'Deletar')
                    ]
                ],
                [
                    'id' => 25,
                    'name' => 'measurement-units_group_view',
                    'description' => 'Unidades de Medida',
                    'children' => [
                        array('id' => 26, 'name' => 'measurement-units_create', 'description' => 'Criar'),
                        array('id' => 27, 'name' => 'measurement-units_view', 'description' => 'Visualizar'),
                        array('id' => 28, 'name' => 'measurement-units_edit', 'description' => 'Editar'),
                        array('id' => 29, 'name' => 'measurement-units_delete', 'description' => 'Deletar')
                    ]
                ],
                [
                    'id' => 30,
                    'name' => 'ncms_group_view',
                    'description' => 'NCMS',
                    'children' => [
                        array('id' => 31, 'name' => 'ncms_view', 'description' => 'Visualizar')
                    ]
                ],
                [
                    'id' => 32,
                    'name' => 'states_group_view',
                    'description' => 'Estados',
                    'children' => [
                        array('id' => 33, 'name' => 'states_view', 'description' => 'Visualizar')
                    ]
                ],
                [
                    'id' => 34,
                    'name' => 'cities_group_view',
                    'description' => 'Cidades',
                    'children' => [
                        array('id' => 35, 'name' => 'cities_view', 'description' => 'Visualizar')
                    ]
                ]
            ]
        ],
        [
            'id' => 36,
            'name' => 'management_group_view',
            'description' => 'Gerenciamento',
            'children' => [
                [
                    'id' => 37,
                    'name' => 'users_group_view',
                    'description' => 'Usuários',
                    'children' => [
                        array('id' => 38, 'name' => 'users_create', 'description' => 'Criar'),
                        array('id' => 39, 'name' => 'users_view', 'description' => 'Visualizar'),
                        array('id' => 40, 'name' => 'users_edit', 'description' => 'Editar'),
                        array('id' => 41, 'name' => 'users_delete', 'description' => 'Deletar')
                    ]
                ],
                [
                    'id' => 42,
                    'name' => 'roles_group_view',
                    'description' => 'Atribuições/Módulos',
                    'children' => [
                        array('id' => 43, 'name' => 'roles_create', 'description' => 'Criar'),
                        array('id' => 44, 'name' => 'roles_view', 'description' => 'Visualizar'),
                        array('id' => 45, 'name' => 'roles_edit', 'description' => 'Editar'),
                        array('id' => 46, 'name' => 'roles_delete', 'description' => 'Deletar')
                    ]
                ],
                [
                    'id' => 47,
                    'name' => 'permissions_group_view',
                    'description' => 'Permissões',
                    'children' => [
                        array('id' => 48, 'name' => 'permissions_view', 'description' => 'Visualizar'),
                        array('id' => 49, 'name' => 'permissions_edit', 'description' => 'Editar')
                    ]
                ]
            ]
        ]
    ];
}
