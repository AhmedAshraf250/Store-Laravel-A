<?php

namespace App\View\Components;

use Illuminate\Support\Facades\Route;
use Illuminate\View\Component;

class Nav extends Component
{

    /**
     * the Second type of components is [class-based components].
     * component have a class and a view.
     * Class-based components allow you to define both: [the template] and [the behavior of the component] in a class.
     * 
     * why to use class-based components?
     * 1- when the component need to do specific tasks like fetching data from database or calling an API.
     * 2- to add logic to the component.
     * 3- reuse the component in multiple places.
     * 4- create a more complex component.
     * 5- when I need to create a component that is not just a simple HTML element.
     */


    /**
     * Create properties for the data you want to pass to the component's view.
     * if the properites was declared as public, it will be automatically available to the component's view.
     * if you want to make them available manually, you can define them as protected properties. and then pass them to the view from the render method.
     */
    public $items;
    public $active;



    /**
     * Create a new component instance.
     *
     * @return void
     */


    public function __construct()
    {
        /**
         * when passing attributes or parameters to a class-based component, LIKE: <x-nav context="side" />
         * they are received in the constructor of the class with the same name and same order.
         * you can also define default values for the parameters in the constructor. LIKE: public function __construct($context = 'default'){ //logic }
         */
        // config files always return an array
        // so I create the nav file inside the config folder to hold the nav items data

        $this->items = $this->prepareItems(config('nav'));
        // $this->active = Route::currentRouteName(); // 
        /**
         *  Route::currentRouteName()
         *      - returns the [name] of the current route as is like 'dashboard.categories.index'
         * 
         * so if I was in categories then go to create category page the current route name will be 'dashboard.categories.create'
         * so the active will fails between 'dashboard.categories.index' and 'dashboard.categories.create'
         * to solve this problem we can use wildcard '*' like 'dashboard.categories.*'
         * so any route that starts with 'dashboard.categories.' will be always active.
         * this is done in the config/nav.php file in the 'active' key for each item.
         *  *  
         *  Route::current()
         *      - returns the current route [instance].
         */
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        // render() method is responsible for returning the view that associated with this component.
        return view('components.dashboard.nav');
    }

    protected function prepareItems($items)
    {
        $user = auth()->user();

        foreach ($items as $key => $item) {
            if (isset($item['ability']) && !$user->can($item['ability'])) {
                unset($items[$key]);
            }
        }

        return $items;
    }
}
