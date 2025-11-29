<?php


/**
 * ==================================================================
 * Repository Design Pattern – Super Simple Explanation
 * ==================================================================
 *
 * The main goal in one sentence:
 * Separate "Where does the data come from?" from "How do I use the data?"
 *
 * Repository Pattern → Change data source (DB ↔ API ↔ Cache) 
 * with just ONE line in AppServiceProvider. Controllers never change.
 * 
 * What does that mean?
 * Your Controller shouldn't need to know whether the data is coming from:
 * → Database (Eloquent)
 * → External API
 * → JSON or Excel file
 * → Cache (Redis)
 * → Or even fake data in tests
 *
 * How do we achieve that?
 * 1. Create an Interface that contains all the methods you'll need
 * 2. Create different classes that implement this interface 
 *    (EloquentRepository, ApiRepository, JsonRepository, etc.)
 * 3. Bind the interface to the actual implementation in just ONE place 
 *    → usually in AppServiceProvider (or a dedicated service provider)
 *
 * The magic result:
 * Tomorrow, if you decide to switch from database → external API
 * → You only change ONE line in the service provider
 * → All your controllers keep working exactly the same – zero changes needed!
 *
 * ==================================================================
 * Common Methods We Usually Need
 * ==================================================================
 *
 * all()          → get all records
 * paginate()     → get records with pagination
 * find($id)      → get record by ID
 * findBySlug($slug) → get record by slug (great for SEO URLs)
 * create($data)  → add new record
 * update($id, $data) → update existing record
 * delete($id)    → delete record
 *
 * Every repository class (whether Eloquent, API, Cache, etc.) 
 * must implement these methods with the same names and same return types.
 */


/**
app/
├── Repositories/
│   ├── Contracts/               ← All the Interfaces go here
│   │   ├── UserRepositoryInterface.php
│   │   ├── PostRepositoryInterface.php
│   │   ├── CartRepositoryInterface.php
│   │   └── OrderRepositoryInterface.php
│   │
│   ├── Eloquent/                ← Database implementations (most common)
│   │   ├── EloquentUserRepository.php
│   │   ├── EloquentPostRepository.php       ← Optional Base class
│   │   ├── EloquentCartRepository.php
│   │   └── EloquentOrderRepository.php
│   │
│   ├── Api/                     ← For external API data sources
│   │   ├── ApiCartRepository.php
│   │   └── ApiOrderRepository.php
│   │
│   └── Cache/                   ← For Redis or caching implementations
│       └── CachedCartRepository.php
│
├── Models/
├── Http/Controllers/
└── Providers/RepositoryServiceProvider.php   ← Most important file!
 */

/**
 * =======================================================================
 * Repository Design Pattern – Full Explanation & Real-World Purpose
 * =======================================================================
 *
 * Main Goal:
 * Completely separate "how data is fetched" from the business logic / controllers
 * So you can change the data source anytime without touching a single controller or service.
 *
 * Possible Data Sources:
 * • Eloquent Model (default in most projects)
 * • External API (REST, GraphQL, SOAP)
 * • Cache (Redis, Memcached), Session
 * • CSV, Excel, JSON, or static files
 * • Fake/Mock data during testing
 *
 * Real Benefits:
 * - Switch from Eloquent → API → Cache? Just change ONE line in the Service Provider
 * - All controllers keep working 100% – no changes needed
 * - Super easy unit & feature testing (just mock the repository)
 * - Follows SOLID principles (especially Dependency Inversion & Interface Segregation)
 *
 * =======================================================================
 * Example: Category Repository (Step by Step)
 * =======================================================================
 */

/*
 * 1. The Interface – The contract that EVERY repository must follow
 */


interface CategoryRepositoryInterface
{
    public function all(): Collection;

    public function paginate(int $perPage = 15);

    public function find(int $id): ?Category;

    public function create(array $data): Category;

    public function update(int $id, array $data): bool;

    public function delete(int $id): bool;

    public function findBySlug(string $slug): ?Category;
}

/*
 * 2. Default Implementation using Eloquent (used in 99% of projects initially)
 */
class EloquentCategoryRepository implements CategoryRepositoryInterface
{
    public function all(): Collection
    {
        return Category::all();
    }

    public function paginate(int $perPage = 15)
    {
        return Category::with('parent')->paginate($perPage);
    }

    public function find(int $id): ?Category
    {
        return Category::find($id);
    }

    public function create(array $data): Category
    {
        return Category::create($data);
    }

    public function update(int $id, array $data): bool
    {
        $category = $this->find($id);
        return $category ? $category->update($data) : false;
    }

    public function delete(int $id): bool
    {
        $category = $this->find($id);
        return $category ? $category->delete() : false;
    }

    public function findBySlug(string $slug): ?Category
    {
        return Category::where('slug', $slug)->first();
    }
}

/*
 * 3. Alternative Implementation – Fetching data from an external API
 */
class ApiCategoryRepository implements CategoryRepositoryInterface
{
    public function all(): Collection
    {
        $response = Http::get('https://api.example.com/categories');

        return collect($response->json()['data']);
    }

    // Other methods would transform API responses into Category models or arrays
    // ... implement the rest similarly
}

/*
 * 4. The Magic Binding – Only place you decide which repository to use
 */
class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Change this ONE line to switch the entire app's data source!
        $this->app->bind(
            CategoryRepositoryInterface::class,
            EloquentCategoryRepository::class
            // ApiCategoryRepository::class   ← uncomment to switch to API
            // CacheCategoryRepository::class  ← or cache version
        );
    }
}

/*
 * 5. Controller Usage – Always stays the SAME (clean & future-proof)
 */
class CategoryController extends Controller
{
    public function __construct(protected CategoryRepositoryInterface $categories)
    {
        // Type-hinted with the interface → never breaks on switching implementation
    }

    public function index()
    {
        $categories = $this->categories->paginate(20);

        return view('categories.index', compact('categories'));
    }

    public function show(string $slug)
    {
        $category = $this->categories->findBySlug($slug);

        return view('categories.show', compact('category'));
    }
}
