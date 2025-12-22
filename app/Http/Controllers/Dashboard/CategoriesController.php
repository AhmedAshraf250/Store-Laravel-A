<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

//use Exception;

class CategoriesController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		// It is best practice to check permissions before executing the action.
		//
		// The currently authenticated user is passed to the Gate closure associated with the ability name "category.view". [look: at AuthServiceProvider.php]
		// Gate::authorize('category.view'); 
		if (!Gate::allows('categories.view')) {
			abort(403); // Forbidden
		}

		$request = request(); // return request object from service container
		/**
		 *	*  * [Explanation]:
		 * 			$query = Category::query(); // return "Illuminate\Database\Eloquent\Builder" Object 	LIKE: 'select * from `categories`'
		 *			- it's useful for building custom queries without applying global scopes 
		 *	*
		 *  *  * [hint] : Category::query() != $request->query()
		 *			- $request->query() -> return all query string parameters from the request.
		 * 			- Category::query() -> return a new query builder for the Category model.
		 *  *
		 *  *  * [previous code before using scopeFilter]:
		 * 			if ($name = $request->query('name')) {
		 *     			$query->where('name', 'LIKE', "%{$name}%");
		 * 			} 
		 * 			if ($status = $request->query('status')) {
		 *     			$query->where('status', '=', $status); // === $query->wherestatus($status);
		 * 			}
		 */
		$categories = Category::with('parent')
			/**
			 * $categories = Category::leftJoin('categories As parents', 'parents.id', '=', 'categories.parent_id')
			 * ->select(['categories.*', 'parents.name AS parent_name'])
			 * 	*
			 * [Result]: "select `categories`.*, `parents`.`name` AS `parent_name` from `categories` left join `categories` As `parents` on `parents`.`id` = `categories`.`parent_id`"
			 * 	* [Explanation]:
			 * 	- I used left join instead of inner join because inner join returns data only when both tables have records, excluding null results. 
			 * 	In this case, some categories have a null parent_id. Left join returns all records from the main table regardless of whether there is a matching record in the second table, including nulls.
			 *	*
			 * 	- if the main table was the second one, we would use right join.
			 */

			// "select `categories`.*, (select count(*) from `products` where `categories`.`id` = `products`.`category_id` and `status` = ?) as `products_count` from `categories` where `categories`.`deleted_at` is null order by `categories`.`name` asc"
			->withCount([ // withCount($relations) returns the count of relation not the result of the relation
				'products' => function ($query) {
					$query->where('status', '=', 'active')
						->withoutGlobalScope('store');
				},
			])  // == [->select('categories.*')->selectRaw('(SELECT COUNT(*) FROM products WHERE category_id = categories.id and status = ?) as products_count')]
			->filter(request()->query())
			->orderBy('categories.name') // ->orderBy('tableName.columnName')
			->paginate(8); // return Collection Object
		// $categories = Category::simplePaginate(1); // « Previous || Next »

		// $parents = Category::all()->pluck('name', 'id')->toArray();

		return view('dashboard.categories.index', compact('categories'/* , 'parents' */));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		if (Gate::denies('categories.create')) {
			abort(403); // Forbidden
		}
		// $category = new Category;
		$cate_parents = Category::all();

		return view('dashboard.categories.create', compact('cate_parents'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{

		Gate::authorize('categories.create'); // If not authorized, automatically throws 403 Forbidden

		/**
		 * عندما يحدث خطأ في رول او قاعدة ما داخل ميثود "فاليدات" في لارافيل، يحدث السيناريو التالي
		 * 
		 # التأكد من صحة البيانات:ك
		 *   - عند استدعاء ميثود فاليدات يقوم لارافيل بمراجعة البيانات المدخلة وفقًا للقواعد المحددة
		 *   - إذا كانت البيانات المدخلة لا تتوافق مع إحدى القواعد، يعتبر ذلك خطأ في التحقق
		 * 
		 # التعامل مع الأخطاء:ك
		 *   - عند حدوث خطأ في التحقق، يتم توليد استثناء من نوع
		 *            - ValidationException
		 *   - يتم القبض على هذا الاستثناء تلقائيًا بواسطة نظام لارافيل
		 * 
		 # إعادة التوجيه:ك
		 *   - يتم إعادة توجيه المستخدم تلقائيًا إلى الصفحة السابقة
		 *   - اللارافيل هترجعنى للصفحة السابقة مع حاجه اسمها "ويز انبتس", ودا يعنى ان اللارافيل راح تخزن الإنبت الحالى فى السيشن كافلاش,
		 *	 - اى بشكل مؤقت فقط للنكست ريكوست بحيث نحصل على هذه القيم لو احتاجنها او للحفاظ على القيم التى ادخلها المستخدم فى الفورم
		 *   - يتم تضمين بيانات الإدخال الأصلية في الجلسة بحيث يمكن إعادة تعبئة النموذج بنفس البيانات التي أدخلها المستخدم (باستثناء الحقول من نوع الملف)
		 * 
		 # عرض الأخطاء:ك
		 *   - يتم تخزين رسائل الأخطاء في الجلسة أيضًا، بحيث يمكن عرضها للمستخدم
		 *   - يمكن الوصول إلى هذه الأخطاء في عرض "البليد" باستخدام متغير $الإيرورس.
		 */

		// $clean_data = $request->validate(Category::rules());
		$request->validate(Category::rules(), [
			// Custom Error Messages | the second argument of validate() method
			'required'  => 'This Field(:attribute) is required',
			'unique'    => 'This Field(:attribute) is Already Exists',
			'image.max' => 'الصورة التى قمت برفعهاأكبر من 2 ميجابايت',
			// spacific 'max' rule message of 'image'  =>  field <input type="file" name="image"> 
			// so not general 'max' rule message for all fields | first spacific {the field} then {the rule } => -exp : 'name.required'
		]);

		/**
		 * # Ways to Access to the data in the Request
		 *     - Single Data
		 *      $request->query('name');
		 *      // action="{{route('categories.store')}}?name=x" -> "x" {"only URL" -> 'query string'}
		 *      $request->input('name');
		 *      // action="{{route('categories.store')}}?name=x" -> "x" {take from 'url' and 'post' in the same time,take from 'url' first}
		 *      $request->get('name');
		 *      // Bring me its value, whether it is in the 'get' or 'post'
		 *      $request->post('name');
		 *      // take value from post request body
		 *      $request->name;
		 *      // The shortcut method
		 *      $request['name'];
		 *      // request is an object which implements "ArrayAccess" So it can be treated as an array
		 *
		 *     - Collection of Data
		 *      $request->all();
		 *      // return array of all input data
		 *      $request->only(['name', 'parent_id']);
		 *      // return array of keys or names which I prompted
		 *      $request->except(['image']);
		 *      // return array of all data except ....
		 *      // we can send "get" and "post" Request in the same time with <form method='post'>
		 */
		// Request Merge [$request->merge([]);]
		// Inject additional data into the request before validation or storage.
		$request->merge([
			'slug' => Str::slug($request->post('name')),
		]);

		$data = $request->except('image'); //return array

		// Upload Files
		$new_image = $this->uploadImage2($request);
		if ($new_image) {
			$data['image'] = $new_image;
		}
		/** 
		 *	if ($request->hasFile('image')) {
		 *		$file = $request->file('image'); // Return Uploaded file object
		 *		// $file->getClientOriginalName();
		 *		// $file->getSize();
		 *		// $file->getClientOriginalExtension();
		 *		// $file->getMimeType();
		 *		$path = $file->store('uploads', ['disk' => 'public']); // Take uploaded file from temp folder to continuously store // retrn the path of the storage
		 *		$data['image'] = $path;
		 *	}	
		 */
		Category::create($data);

		// at the final we must implements "PRG"->'Post Redirect Get', which mean every 'post' request redirect 'get
		// PRG Pattern → POST → Redirect (GET) → View
		// Prevents "Resubmit form?" on page refresh
		// Clears session + avoids duplicate submissions
		// return redirect(route('categories.index'));
		return redirect()->route('dashboard.categories.index')->with('success', 'Category created successfully');
		// ->with() → flash data (available only on next request)
		// 		- Stored in session, auto-removed after use
		// 		- Perfect for one-time alerts: success, error, info, warning	
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function show(Category $category)
	{
		Gate::authorize('categories.view'); // If not authorized, throws 403 Forbidden

		/**
		 * Implicit Route Model Binding
		 *
		 * Laravel resolves Eloquent models directly from route parameters.
		 *
		 * Benefits:
		 * - Eliminates repetitive findOrFail() calls
		 * - Automatically returns 404 if model not found
		 * - Reduces controller bloat and improves readability
		 * - Type-hinting enables IDE auto-completion and static analysis
		 *
		 * Rule: Route parameter name === method parameter name
		 *       → Route::get('{user}', ...) → public function show(User $user)
		 */
		// Route::get('/cate/{$cate}/show', [Category::class,'show]) ---->  public function show(Category $cate){} // Must Same Parameter
		return view('dashboard.categories.show', ['category' => $category]);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id)
	{
		Gate::authorize('categories.update');

		try {
			$category = Category::find($id);
			if (! $category) {
				throw new \Exception('Category not found');
			}
		} catch (\Exception $e) {
			// abort(404);
			return redirect()->route('dashboard.categories.index')
				->with('info', $e->getMessage());
		}
		// OR =>
		// if (!$category) {
		//     abort(404);
		// }

		// "select * from `categories` where `id` <> ? and (`parent_id` is null or `parent_id` <> ?)"
		$cate_parents = Category::where('id', '<>', $id)
			->where(function ($query) use ($id) {
				$query->whereNull('parent_id')
					->orWhere('parent_id', '<>', $id);
			})->get();

		return view(
			'dashboard.categories.edit',
			compact('category', 'cate_parents')
		);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function update(CategoryRequest $request, $id)
	{
		// Gate::authorize('category.update');

		// $request->validate(Category::rules($id));

		$category = Category::find($id);
		if (! $category) {
			abort(404);
		}
		$old_image = $category->image;
		$data      = $request->except('image');

		// Upload Files
		$new_image = $this->uploadImage2($request);
		if ($new_image) {
			$data['image'] = $new_image;
		}
		if (!$request->name != $category->name) {
			$data['slug'] = Str::slug($request->name);
		}
		$category->update($data);
		// $category->fill($request->all())->save();
		if ($old_image && $data['image']):
			Storage::disk('public')->delete($old_image);
		endif;

		return redirect()->route('dashboard.categories.index')
			->with('success', 'Category updated successfully');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		Gate::authorize('categories.delete');

		$category = Category::findorfail($id);
		$category->delete();

		// Category::where('id', '<>', $id)->delete();
		// Category::destroy($id); // $id refer to primary key of that model

		return redirect()->route('dashboard.categories.index')
			->with('success', 'Category deleted successfully');
	}

	public function trash()
	{
		$categories = Category::onlyTrashed()->paginate();
		return view('dashboard.categories.trash', compact('categories'));
	}

	public function restore(Request $request, $id)
	{
		$categories = Category::onlyTrashed()->findOrFail($id);
		$categories->restore();

		return redirect()
			->route('dashboard.categories.trash')
			->with('success', 'Categories restored successfully');
	}

	public function forceDelete($id)
	{
		$categories = Category::onlyTrashed()->findOrFail($id);
		// $categories->forceDelete();
		if ($categories->forceDelete()) {
			Storage::disk('public')->delete($categories);
		}

		return redirect()
			->route('dashboard.categories.trash')
			->with('success', 'Categories deleted forever');
	}
	protected function uploadImage2(Request $request)
	{
		if (! $request->hasFile('image')) {
			return;
		}
		$file = $request->file('image');
		$path = $file->store('uploads', ['disk' => 'public']);

		return $path;
	}
}
