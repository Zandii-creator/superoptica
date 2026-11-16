<?php

namespace App\Livewire;

use Illuminate\Contracts\View\View;
use Livewire\Component;

class CatalogSearch extends Component
{
    public string $q = '';

    /**
     * Filtros activos que hay que conservar al buscar.
     *
     * @var array<string, string>
     */
    public array $activeFilters = [];

    /**
     * @param  array<string, string>  $activeFilters
     */
    public function mount(array $activeFilters = []): void
    {
        $this->q = (string) request()->query('q', '');
        $this->activeFilters = array_filter($activeFilters);
    }

    public function updatedQ(): void
    {
        $this->search();
    }

    public function search(): void
    {
        $this->redirect($this->targetUrl(), navigate: true);
    }

    public function render(): View
    {
        return view('livewire.catalog-search');
    }

    private function targetUrl(): string
    {
        $query = $this->activeFilters;

        if (trim($this->q) !== '') {
            $query['q'] = trim($this->q);
        }

        return route('catalog.index', $query);
    }
}
