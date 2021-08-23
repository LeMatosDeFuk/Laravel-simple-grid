# Laravel Simple Grid [~Alpha]

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
3. Specify grid columns as public properties
4. `render()` function should look like this:
   ```
   $grid = $this->createGrid();  
   
   return view('Your-view', compact('grid'));
   ```
5. Inside `createGrid()` specify columns you want to show
6. Inside `filterData()` filter your query with your public specified properties

## Columns parameters

### Normal Input

`$grid->addColumn()`

```
    /**
     * @param string      $label - Column name shown to user
     * @param string      $name - name of attribute from database
     * @param string      $operator - operator in query ('like', '<=', '>=', ...)
     * @param bool        $isLazy - this argument is used mainly for datepicker, it specifies if wire.model is lazy or not
     * @param string      $type - type of column ('text', 'number')
     */
```

### Select Input

`$grid->addSelect()`

```
     * @param string      $label - Column name shown to user
     * @param string      $name - name of attribute from database
     * @param array       $data - to this argument you can pass data which are shown in select as options
     * @param bool        $search - specify, if you want search input or not
     * @param string      $operator - operator in query ('like', '<=', '>=', ...)
     * @param bool        $isLazy - this argument is used mainly for datepicker, it specifies if wire.model is lazy or not
     ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
```

### Date Input

`$grid->addColumnDate()`

```
    /**
     * @param string      $label - Column name shown to user
     * @param string      $name - name of attribute from database
     * @param string      $type - type of column ('date', 'datetime')
     */
```

**Here is an example of my ProductGrid**

```<?php

namespace App\Http\Livewire\DataManagement\Product;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use LeMatosDeFuk\Grid;
use LeMatosDeFuk\GridComponent;
use LeMatosDeFuk\GridInterface;

class ProductGrid extends GridComponent implements GridInterface
{
    public string $table   = 'products';
    public string $sort_by = 'name';

    public function render()
    {
        $grid = $this->createGrid();

        return view('livewire.data-management.product.grid', compact('grid'));
    }

    public function createGrid(): Grid
    {
        $grid = new Grid($this);
        $grid->addColumn('ID');
        $grid->addColumn('Name');
        $grid->addColumn('Price', operator: '<=');

        $grid->addSelect('Brand', 'brand_id')
             ->setSortBy('brands.name')
             ->setModel(Brand::class)
             ->setRenderer([$this, 'renderBrand'])
             ->setFilter([$this, 'filterBrand']);

        $grid->addSelect('Category', 'category_id')
             ->setSortBy('categories.name')
             ->setModel(Category::class)
             ->setRenderer([$this, 'renderCategory'])
             ->setFilter([$this, 'filterCategory']);

        $grid->addColumn('Stocks', 'number_of_stocks', '<=');
        $grid->addSelect('Until Sold Out', 'until_out_of_stocks', search: false);
        $grid->addSelect('Available', 'is_available', search: false);

        $grid->addColumnDate('Date Created', 'created_at')
             ->setRenderer([$this, 'renderDateCreated']);

        $grid->addActions();
        $grid->setModels();

        return $grid;
    }

    public function filterCategory(Builder $query, $value): Builder
    {
        return $query->whereHas('category', fn ($query) => $query->where('id', (int)$value));
    }

    public function filterBrand(Builder $query, $value): Builder
    {
        return $query->whereHas('brand', fn ($query) => $query->where('id', (int)$value));
    }

    public function renderCategory(Product $product): string
    {
        return $product->getCategoryName();
    }

    public function renderBrand(Product $product): string
    {
        return $product->getBrandName();
    }

    public function renderUntilOutOfStocks(Product $product): string
    {
        return $product->getUntilOutOfStocks(true);
    }

    public function renderIsAvailable(Product $product): string
    {
        return $product->getIsAvailable(true);
    }

    public function renderDateCreated(Product $product): string
    {
        return $product->getDateCreated();
    }

    public function filterData(): Builder
    {
        return Product::query()
                      ->join('categories', 'products.category_id', '=', 'categories.id')
                      ->join('brands', 'products.brand_id', '=', 'brands.id')
                      ->select(['products.*', 'categories.name as category_name', 'brands.name as brand_name']);
    }

}
```
