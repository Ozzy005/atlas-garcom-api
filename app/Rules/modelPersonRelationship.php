<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\InvokableRule;
use Illuminate\Database\Eloquent\Builder;

class modelPersonRelationship implements InvokableRule
{
    /** @var \Illuminate\Database\Eloquent\Model $model */
    private readonly string $model;
    private readonly int | null $ignore;

    public function __construct(string $model, int | null $ignore)
    {
        $this->model = $model;
        $this->ignore = $ignore;
    }

    /**
     * Run the validation rule.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     * @return void
     */
    public function __invoke($attribute, $value, $fail)
    {
        $model = $this->model::query()
            ->whereHas('person', function (Builder $query) use ($attribute, $value) {
                $query->where($attribute, $value)
                    ->when(!empty($this->ignore), fn (Builder $query) => $query->where('id', '!=', $this->ignore));
            })
            ->first();

        if (!empty($model)) {
            $fail('validation.unique')->translate();
        }
    }
}
