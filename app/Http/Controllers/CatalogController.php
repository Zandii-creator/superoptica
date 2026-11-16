<?php

namespace App\Http\Controllers;

use App\Enums\Gender;
use App\Enums\ProductType;
use App\Models\Brand;
use App\Models\Material;
use App\Models\Product;
use App\Models\Shape;
use App\Models\SiteSettings;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    private const PER_PAGE = 12;

    public function index(Request $request): View
    {
        $brands = Brand::query()->active()->orderBy('name')->get();
        $materials = Material::query()->active()->orderBy('sort')->orderBy('name')->get();
        $shapes = Shape::query()->active()->orderBy('sort')->orderBy('name')->get();

        $brand = $brands->firstWhere('slug', (string) $request->query('marca'));
        $material = $materials->firstWhere('slug', (string) $request->query('material'));
        $shape = $shapes->firstWhere('slug', (string) $request->query('forma'));
        $type = ProductType::tryFrom((string) $request->query('tipo'));
        $gender = Gender::tryFrom((string) $request->query('genero'));
        $search = trim((string) $request->query('q'));

        $products = Product::query()
            ->published()
            ->with(['brand', 'material', 'shape', 'variants'])
            ->when($brand, fn (Builder $query, Brand $value) => $query->where('brand_id', $value->id))
            ->when($material, fn (Builder $query, Material $value) => $query->where('material_id', $value->id))
            ->when($shape, fn (Builder $query, Shape $value) => $query->where('shape_id', $value->id))
            ->when($type, fn (Builder $query, ProductType $value) => $query->where('type', $value->value))
            ->when($gender, fn (Builder $query, Gender $value) => $query->where('gender', $value->value))
            ->when($search !== '', fn (Builder $query) => $query->where(
                fn (Builder $matches) => $matches
                    ->where('model', 'like', '%'.$search.'%')
                    ->orWhereHas('brand', fn (Builder $brandQuery) => $brandQuery->where('name', 'like', '%'.$search.'%'))
            ))
            ->orderByDesc('is_featured')
            ->orderByDesc('published_at')
            ->orderBy('id')
            ->paginate(self::PER_PAGE)
            ->withQueryString();

        $activeFilters = $this->activeFilters($request, $brand, $material, $shape, $type, $gender, $search);

        $settings = SiteSettings::current();

        return view('catalog.index', [
            'products' => $products,
            'brands' => $brands,
            'materials' => $materials,
            'shapes' => $shapes,
            'types' => ProductType::cases(),
            'genders' => Gender::cases(),
            'brand' => $brand,
            'material' => $material,
            'shape' => $shape,
            'type' => $type,
            'gender' => $gender,
            'search' => $search,
            'activeFilters' => $activeFilters,
            'queryFilters' => collect($activeFilters)->map(fn (array $filter): string => $filter['value'])->all(),
            'settings' => $settings,
            'title' => $brand
                ? 'Monturas '.$brand->name.' en Ciego de Ávila'
                : 'Catálogo de espejuelos, monturas y lentes de contacto',
            'description' => $settings->seo_description
                ?: 'Catálogo de espejuelos, monturas y lentes de contacto en Ciego de Ávila. Consulta modelos y precios en CUP.',
            'canonical' => $request->fullUrlWithoutQuery('page'),
        ]);
    }

    /**
     * Filtros activos con su etiqueta y el enlace que los quita.
     *
     * @return array<string, array{param: string, label: string, value: string, remove_url: string}>
     */
    private function activeFilters(
        Request $request,
        ?Brand $brand,
        ?Material $material,
        ?Shape $shape,
        ?ProductType $type,
        ?Gender $gender,
        string $search,
    ): array {
        $filters = [];

        if ($brand) {
            $filters['marca'] = ['param' => 'marca', 'label' => 'Marca: '.$brand->name, 'value' => $brand->slug];
        }

        if ($type) {
            $filters['tipo'] = ['param' => 'tipo', 'label' => $type->getLabel(), 'value' => $type->value];
        }

        if ($gender) {
            $filters['genero'] = ['param' => 'genero', 'label' => $gender->getLabel(), 'value' => $gender->value];
        }

        if ($material) {
            $filters['material'] = ['param' => 'material', 'label' => 'Material: '.$material->name, 'value' => $material->slug];
        }

        if ($shape) {
            $filters['forma'] = ['param' => 'forma', 'label' => 'Forma: '.$shape->name, 'value' => $shape->slug];
        }

        if ($search !== '') {
            $filters['q'] = ['param' => 'q', 'label' => 'Búsqueda: '.$search, 'value' => $search];
        }

        return collect($filters)
            ->map(fn (array $filter): array => $filter + [
                'remove_url' => $request->fullUrlWithoutQuery($filter['param']),
            ])
            ->all();
    }
}
