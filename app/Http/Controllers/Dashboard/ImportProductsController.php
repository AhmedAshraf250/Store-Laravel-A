<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Jobs\ImportProducts;
use Illuminate\Http\Request;

class ImportProductsController extends Controller
{
    public function index()
    {
        return view('dashboard.products.import');
    }

    public function store(Request $request)
    {
        // $job = new ImportProducts($request->post['count']);
        // $job->onQueue('import');
        // // ->onConnection('redis'); // ->onConnection('sync');
        // $this->dispatch($job);

        ImportProducts::dispatch($request->post('count'))->onQueue('import'); //->onConnection('database');

        return redirect()
            ->route('dashboard.products.index')
            ->with('success', 'Import is runing...');
    }
}
