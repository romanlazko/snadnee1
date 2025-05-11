<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Table\StoreTableRequest;
use App\Http\Requests\Table\UpdateTableRequest;
use App\Models\Table;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TableController extends Controller
{
    public function index(): View
    {
        $tables = Table::paginate(10);

        return view('admin.table.index', [
            'tables' => $tables,
        ]);
    }

    public function create(): View
    {
        return view('admin.table.create');
    }

    public function store(StoreTableRequest $request): RedirectResponse
    {
        Table::create($request->validated());

        return redirect()->route('admin.table.index');
    }

    public function edit(Table $table): View
    {
        return view('admin.table.edit', [
            'table' => $table,
        ]);
    }

    public function update(UpdateTableRequest $request, Table $table): RedirectResponse
    {
        $table->update($request->validated());

        return redirect()->route('admin.table.index');
    }

    public function destroy(Table $table): RedirectResponse
    {
        $table->delete();

        return redirect()->route('admin.table.index');
    }
}
