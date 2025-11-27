<?php

namespace App\Models;

use App\Rules\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Validation\Rule;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    // white list
    protected $fillable = ['name', 'slug', 'parent_id', 'image', 'description', 'status'];
    // black list
    protected $guarded = [];


    public static function rules($id = 0)
    {
        return [
            /**
             * Why exclude the current record in unique validation during update?
             *
             * When editing a category:
             * - If the 'name' remains unchanged, the unique validation would fail because it finds the same name in the database (the current record itself).
             * - To prevent this, we exclude the current record's ID from the unique check,
             * - fix : ignore the current record by its id
             * - we passed $id to the rules method to identify the current record.
             */

            // 'name' => "required|string|min:3|max:255|unique:categories,name,{$id}",
            'name' => [
                'required',
                'string',
                'min:3',
                'max:255',
                Rule::unique('categories', 'name')->ignore($id),


                // to make Custom Rule, there are 3 ways:    || this only for explanation (for me) || not to be included in the code ||
                /**
                    1- This way is local and only used here within this validation array
                 *      > Using a Closure (Anonymous Function) as a custom validation rule
                 *          - Signature:
                 *            function (string $attribute, mixed $value, Closure $fails): void
                 *      > This function takes 3 arguments: $attribute, $value, $fails    - example: <input name="name" value="">
                 *          - $attribute: Represents the name of the field, which will always be "name" in this case.
                 *          - $value: The value entered in this field, i.e., the value present in the request.
                 *      > Inside the function, we implement the logic to check
                 */
                function ($attribute, $value, $fails) {
                    if (strtolower($value) == 'laravel') {
                        $fails("This {$attribute} is Forbidden.");
                    }
                },

                /**
                    2- This way is global and can be used in multiple places, not just limited to the file where we define it.
                 *
                 *  Use this when the validation logic is **shared across multiple forms/controllers**.
                 * Steps:
                 * 1. Generate the rule:    > php artisan make:rule Filter               // App\Rules\Filter.php
                 *          Generated class structure:
                 *          ├─ passes($attribute, $value): bool  → Core validation logic
                 *          ├─ message(): string|array          → Custom error message
                 * 2. Implement the logic in the generated class:
                 * 3. Use the rule instance in validation:    -example : 'content' => [new FilterForbiddenWords],
                 */
                new Filter(['php', 'html']),

                /**
                    3- using a Custom Rule class combined with the Validator Facade to extend the [validation system]:
                        by [Macros System] is a feature in Laravel that allows us to add custom methods to existing classes without modifying their source code.
                 *  - This method allows us to define a custom validation rule that can be reused throughout the application.
                 * Steps:
                 * 1- Define the custom rule using Validator::extend:
                 *     - This is typically done in a service provider's boot method (e.g., AppServiceProvider).
                 * 2- Implement the logic for the custom rule using a Closures:
                 *      - App\Providers\AppServiceProvider.php
                            Validator::extend('filter', function ($attribute, $value, $parameters) {
                                return !in_array(strtolower($value), $parameters);
                            }, 'The value you entered is forbidden.');
                 * 3- Use the custom rule in validation: - Example: 'name' => ['filter:css,js,python'],
                 *
                    [SUMMARY:]
                 * if the rule is used here only use the first way (Closure)
                 * if the rule is used in multiple places in hole application use the second way or the third way
                 */
                'filter:css,js,python'
            ],
            'parent_id' => ['nullable', 'integer', 'exists:categories,id'],
            'image' => ['image', 'max:1047576', 'dimensions:min_width=100,min_height=100', 'mimetypes:image/jpeg,image/png,image/jpg,image/gif,image/svg+xml'],
            'status' => 'in:active,archived'
        ];
    }

    // [Scopes]:
    /**
     *  [Scopes]:
     *      - Local Scopes: (Defined within the model itself)
     *         > used to if we want to reuse a query logic multiple times within the same model.
     *         > helps to keep the code DRY (Don't Repeat Yourself) by encapsulating common query logic.
     *         > always returns a Builder instance to allow for method chainin,
     *         > scope always recieves the query builder so that we can add conditions to it.
     *         > return Builder Object even if not defined in parameters.
     *         - How to define and use Local Scopes:
     *            1. Define a method in the model with the prefix "scope" followed by the scope name LIKE : scopeActive, scopeStatus, etc.
     *            2. when calling the scope, use the scope name without the "scope" prefix.          LIKE : Category::active()->get();
     *
     *      - Global Scopes: (Defined as separate classes that implement the Scope interface)
     *         > applied automatically to all queries for a given model.
     */

    // I added the table name before each column name to avoid unexpected conflicts when using a join statement before the scope.
    public function scopeActive(Builder $builder)
    {
        $builder->where('categories.status', 'active');
        // Category::active(); // to call and apply it
    }

    public function scopeStatus(Builder $builder, $status) // Dynamic Scope
    {
        $builder->where('categories.status', $status);
        // Category::status('active'); // to call and apply it
    }

    public function scopeFilter(Builder $builder, $filters)
    {
        // when() method will pass the value to the closure only if the value is truthy
        $builder->when($filters['name'] ?? false, function ($builder, $value) {
            $builder->where('categories.name', 'LIKE', "%{$value}%");
        });
        // if ($name = $filters['name'] ?? false) { // $name here is assignment not comparison
        //     $builder->where('categories.name', 'LIKE', "%{$name}%");
        // }

        if ($status = $filters['status'] ?? false) {
            $builder->where('categories.status', '=', $status);
        }
        // $categories = Category::filter(request()->query())->Paginate(2); // example in controller
    }


    // [Relations]:

    // 'Category' may have one parent only & the same time could have many children categories
    // named it parent() for clarity and indication
    public function parent()
    {
        // if the relationship returns null, which means no result, we can define a default value, 
        return $this->belongsTo(Category::class, 'parent_id', 'id')->withDefault([
            'name' => '-', // 'name' Column in categories Model which Returned from the relation
        ]);
    }

    public function childern()
    {
        return $this->hasMany(Category::class, 'parent_id', 'id');
    }


    public function products()
    {
        return $this->hasMany(Product::class, 'category_id', 'id');
    }

    public function scopeWhereNotDescendantOf($query, $category)
    {
        return $query->whereNotIn('id', function ($q) use ($category) {
            $q->select('id')
                ->from('categories')
                ->where('parent_id', $category->id);
        })
            ->where(function ($q) use ($category) {
                $q->whereNull('parent_id')
                    ->orWhere('parent_id', '!=', $category->id);
            });
    }
}
