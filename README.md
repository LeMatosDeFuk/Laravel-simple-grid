# Laravel Simple Grid [~Alpha~]

Install livewire `https://laravel-livewire.com/docs/2.x/quickstart`

Install package with `composer require lematosdefuk/laravel-simple-grid`

You can customize grid views with `php artisan vendor:publish --tag=lematosdefuk-views`

Install this package `https://github.com/bastinald/laravel-livewire-modals`

This package support translations

**Support only for bootstrap 5**

***

## Grid setup

1. Create component inside App\Http\Livewire; with `php artisan make:livewire UserGrid`
2. Extends component with `GridComponent` and implements `GridInterface`
3. Specify grid columns as public properties and also put them into $queryString

**Please, keep in mind you need to add sort_dir and sort_by to $queryStrig!!**

4. `render()` function should look like this:
   ```
   $grid = $this->createGrid();  
   $grid->setModels($this);
   
   return view('Your-view', compact('grid'));
   ```
5. Inside `createGrid()` specify columns you want to show
6. Inside `filterData()` filter your query with your public specified properties

## Columns parameters

******************************
**!!!! IMPORTANT !!!!**

Don't add column with `id` as column name. Livewire has `id` reserved for itself. Use `model_id` instead.
******************************

### Normal Input

`$grid->addColumn()`

```
    /**
     * @param string      $label - Column name shown to user
     * @param string      $name - name of attribute from database
     * @param string|null $function - you can return row value by specifying function, eg. 'getFullName' with call $user->getFullName()
     * @param mixed|null  $functionArguments - if you specify function, this variable is passed to that function, eg. 'xxx' will be called as $user->getFullName('xxx')
     * @param bool        $isLazy - this argument is used mainly for datepicker, it specifies if wire.model is lazy or not
     * @param string      $type - type of column ('text', 'number')
     */
```

### Select Input

`$grid->addSelect()`

```
     * @param string      $label - Column name shown to user
     * @param string      $name - name of attribute from database
     * @param string|null $function - you can return row value by specifying function, e.g. 'getRoleName' with call $user->getRoleName()
     * @param array       $data - to this argument you can pass data which are shown in select as options
     * @param mixed|null  $functionArguments - if you specify function, this variable is passed to that function, e.g. 'random' will be called as $user->getRoleName('random')
     * @param bool        $isLazy - this argument is used mainly for datepicker, it specifies if wire.model is lazy or not
     * @param string      $type - type of column ('select')
     * @param bool        $search - specify, if you want search input or not
     * @param string|null $model - if it's relationship column, specify which model is related, e.g. Role::class
     * @param string|null $relationSort
     ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
```

Parameter relationSort is a litte bit more complicated. This parameter specifies, which column from relationship you
want to call. This name is specified in filterData() function

```
     public function filterData() {
              return User::query()
                    ->join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
                    ->select(['users.*', 'model_has_roles.role_id as role']);
     }
```

So parameter $relationSort should contain 'role'.

**Another example:**

```     
     public function filterData()
     {
              return Product::query()
                    ->join('categories', 'products.category_id', '=', 'categories.id')
                    ->join('brands', 'products.brand_id', '=', 'brands.id')
                    ->select(['products.*', 'categories.name as category_name', 'brands.name as brand_name'])
     }
```

So parameter $relationSort in category column should contain: 'category_name' and brand column should contain: '
brand_name'

### Date Input

`$grid->addColumnDate()`

```
    /**
     * @param string      $label - Column name shown to user
     * @param string      $name - name of attribute from database
     * @param string|null $function - you can return row value by specifying function, e.g. 'getDateCreated' with call $user->getDateCreated()
     * @param mixed|null  $functionArguments - if you specify function, this variable is passed to that function, e.g. 'd.m.Y' will be called as $user->getDateCreated('d.m.Y')
     * @param bool        $isLazy - this argument is used mainly for datepicker, it specifies if wire.model is lazy or not
     * @param string      $type - type of column ('date')
     */
```

**Here is an example of UserGrid**

```

<?php

namespace App\Http\Livewire\DataManagement\User;

use App\Models\User;
use Carbon\Carbon;
use LeMatosDeFuk\Grid;
use LeMatosDeFuk\GridComponent;
use LeMatosDeFuk\GridInterface;

class UserGrid extends GridComponent implements GridInterface
{
    public $model_id;
    public $name;
    public $role;
    public $created_at;

    protected $queryString = [
        'model_id',
        'name',
        'role',
        'created_at',
        'sort_by',
        'sort_dir'
    ];

    public function render()
    {
        $grid = $this->createGrid();
        $grid->setModels($this);

        return view('livewire.data-management.user.grid', compact('grid'));
    }

    public function createGrid(): Grid
    {
        $grid = new Grid();
        $grid->addColumn('ID', 'model_id');
        $grid->addColumn('Name', 'name');
        $grid->addSelect(
            label:    'Role',
            name:     'role',
            function: 'getTranslatedRole',
            data:      User::getTranslatedRoles(),
            search:    false,
        );
        $grid->addColumnDate('Date Created', 'created_at', 'getDateCreated', true);
        $grid->addActions();

        return $grid;
    }

    public function filterData()
    {
        return User::query()
                   ->join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
                   ->select(['users.*', 'model_has_roles.role_id as role'])
                   ->when($this->model_id, fn($query, $value) => $query->where('id', 'like', '%' . $value . '%'))
                   ->when($this->name, fn($query, $value) => $query->where('name', 'like', '%' . $value . '%'))
                   ->when($this->created_at, fn($query, $value) => $query->whereDate('created_at', Carbon::parse($this->created_at)))
                   ->when($this->role, fn($query, $value) => $query->whereHas('roles', fn($query) => $query->where('name', $value)));
    }

}
```

**Another example**

```
<?php

namespace App\Http\Livewire\DataManagement\Product;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Carbon\Carbon;
use LeMatosDeFuk\Grid;
use LeMatosDeFuk\GridComponent;
use LeMatosDeFuk\GridInterface;

class ProductGrid extends GridComponent implements GridInterface
{
    public $model_id;
    public $name;
    public $price;
    public $brand_id;
    public $category_id;
    public $is_available;
    public $stocks;
    public $created_at;

    protected $products;
    protected $queryString = [
        'model_id',
        'brand_id',
        'category_id',
        'name',
        'price',
        'until_out_of_stocks',
        'stocks',
        'is_available',
        'created_at',
        'sort_by',
        'sort_dir'
    ];

    public function render()
    {
        $grid = $this->createGrid();
        $grid->setModels($this);

        return view('livewire.data-management.product.grid', compact('grid'));
    }

    public function createGrid(): Grid
    {
        $arrayData = [1 => 'Yes', 0 => 'No'];

        $grid = new Grid();
        $grid->addColumn('ID', 'model_id');
        $grid->addColumn('Name', 'name');
        $grid->addColumn('Price', 'price');
        $grid->addSelect(
            label:        'Brand',
            name:         'brand_id',
            function:     'getBrandName',
            model:        Brand::class,
            relationSort: 'brand_name'
        );
        $grid->addSelect(
            label:        'Category',
            name:         'category_id',
            function:     'getCategoryName',
            model:        Category::class,
            relationSort: 'category_name'
        );
        $grid->addColumn('Stocks', 'stocks');
        $grid->addSelect(
            label:             'Available',
            name:              'is_available',
            function:          'getIsAvailable',
            data:              $arrayData,
            functionArguments: true,
            search:            false
        );
        $grid->addColumnDate('Date Created', 'created_at', 'getDateCreated');
        $grid->addActions();

        return $grid;
    }

    public function filterData()
    {
        return Product::query()
                      ->join('categories', 'products.category_id', '=', 'categories.id')
                      ->join('brands', 'products.brand_id', '=', 'brands.id')
                      ->select(['products.*', 'categories.name as category_name', 'brands.name as brand_name'])
                      ->when($this->model_id, fn($query, $value) => $query->where('id', 'like', '%' . $value . '%'))
                      ->when($this->name, fn($query, $value) => $query->where('name', 'like', '%' . $value . '%'))
                      ->when($this->price, fn($query, $value) => $query->where('price', '<=', (float)$value))
                      ->when($this->stocks, fn($query, $value) => $query->where('stocks', '<=', (int)$value))
                      ->when($this->until_out_of_stocks, fn($query, $value) => $query->where('until_out_of_stocks', $value === 'true'))
                      ->when($this->is_available, fn($query, $value) => $query->where('is_available', $value === 'true'))
                      ->when($this->brand_id, fn($query, $value) => $query->whereHas('brand', fn($query) => $query->where('id', (int)$value)))
                      ->when($this->category_id, fn($query, $value) => $query->whereHas('category', fn($query) => $query->where('id', (int)$value)))
                      ->when($this->created_at, fn($query, $value) => $query->whereDate('created_at', Carbon::parse($this->created_at)));
    }

}
```
