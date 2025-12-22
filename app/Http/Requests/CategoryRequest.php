<?php

namespace App\Http\Requests;

use App\Models\Category;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class CategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // 
        if ($this->route('category')) {
            return Gate::allows('categories.update');
        }
        // return Gate::allows('categories.update') || Gate::allows('categories.create');
        return Gate::allows('categories.create');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        /** 
         *    return [
         *        'name' => ['required', 'string', 'min:3', 'max:255', Rule::unique('categories', 'name')->ignore($id)],
         *        'parent_id' => ['nullable', 'integer', 'exists:categories,id'],
         *       'image' => ['image', 'max:1047576', 'dimensions:min_width=100,min_height=100', 'mimetypes:image/jpeg,image/png,image/jpg,image/gif,image/svg+xml'],
         *        'status' => 'in:active,archived'
         *    ];
         * 
            OR

         *   > php artisan route:list
         *      GET|HEAD        dashboard/categories/{category}/edit  ||| for example http://localhost:8000/dashboard/categories/11/edit
         *   $this->route('categories');  // return 11
         * 
                this class is extending FormRequest which extends the base Request class
                and the base Request class has a method called route() which allows us to access route parameters.
         */
        $id = $this->route('category');
        // look   > php artisan route:list   // i get the "parameter name" form url [dashboard/categories/{category}]

        // in Category Model we defined a method called rules() which needs an argument, so we call it here and pass the $id to it
        return Category::rules($id);
    }

    /**
     * if we want to customize the error messages for validation rules, we can do so by overriding the messages() method in the FormRequest class.
     * @return array<string, string>
     */
    public function messages()
    {
        return [
            // 'required' => 'This Field(:attribute) is required',
            // 'unique' => 'This Field(:attribute) is Already Exists',
            // 'image.max' => 'الصورة التى قمت برفعهاأكبر من 2 ميجابايت'
        ];
    }
}
