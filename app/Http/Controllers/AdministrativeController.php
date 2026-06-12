<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAdminRequest;
use App\Http\Requests\UpdateAdminRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AdministrativeController extends Controller
{
    use \App\Traits\UserPhotoFileStorage;

    public function index(Request $request)
    {
        $filterByName = $request->input('name');
        $filterByEmail = $request->input('email');
        $filterByType = $request->input('user_type');
        $filterByBlocked = $request->input('blocked');

        $query = User::whereIn('user_type', ['A', 'F']);

        if ($filterByName) {
            $query->where('name', 'like', '%' . $filterByName . '%');
        }

        if ($filterByEmail) {
            $query->where('email', 'like', '%' . $filterByEmail . '%');
        }

        if ($filterByType) {
            $query->where('user_type', $filterByType);
        }

        if ($filterByBlocked !== null && $filterByBlocked !== '') {
            $query->where('blocked', $filterByBlocked);
        }

        $administratives = $query
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        return view('administratives.index', compact(
            'administratives',
            'filterByName',
            'filterByEmail',
            'filterByType',
            'filterByBlocked'
        ));
    }

    public function create(): View
    {
        $this->authorize('create', User::class);

        $administrative = new User();
        $administrative->user_type = 'A';

        return view('administratives.create', compact('administrative'));
    }

    public function store(StoreAdminRequest $request): RedirectResponse
    {
        // authorize() tratado no StoreAdministrativeRequest
        $validated = $request->validated();

        $administrative = new User();
        $administrative->user_type = $validated['user_type'];
        $administrative->name     = $validated['name'];
        $administrative->email    = $validated['email'];
        $administrative->gender   = $validated['gender'];
        $administrative->password = Hash::make('123');
        $administrative->save();

        $this->storeUserPhoto($request->photo_file, $administrative);

        $url = route('administratives.show', $administrative);
        return redirect()->route('administratives.index')
            ->with('alert-type', 'success')
            ->with('alert-msg', "O administrativo <a href='$url'><u>{$administrative->name}</u></a> foi criado com sucesso!");
    }

    public function show(User $administrative): View
    {
        $this->authorize('view', $administrative);

        return view('administratives.show', compact('administrative'));
    }

    public function edit(User $administrative): View
    {
        $this->authorize('update', $administrative);

        return view('administratives.edit', compact('administrative'));
    }

    public function update(UpdateAdminRequest $request, User $administrative): RedirectResponse
    {
        // authorize() tratado no UpdateAdministrativeRequest
        $validated = $request->validated();

        $administrative->user_type = $validated['user_type'];
        $administrative->name      = $validated['name'];
        $administrative->email     = $validated['email'];
        $administrative->gender    = $validated['gender'];
        $administrative->save();

        if ($request->hasFile('photo_file')) {
            $this->deleteUserPhoto($administrative);
            $this->storeUserPhoto($request->file('photo_file'), $administrative);
        }

        $url = route('administratives.show', $administrative);
        return redirect()->route('administratives.index')
            ->with('alert-type', 'success')
            ->with('alert-msg', "O administrativo <a href='$url'><u>{$administrative->name}</u></a> foi atualizado com sucesso!");
    }

    public function destroy(User $administrative): RedirectResponse
    {
        $this->authorize('delete', $administrative);

        $fileName = $administrative->photo_url;
        $name     = $administrative->name;

        $administrative->delete();
        $this->deletePhotoFile($fileName);

        return redirect()->route('administratives.index')
            ->with('alert-type', 'success')
            ->with('alert-msg', "O administrativo {$name} foi eliminado com sucesso!");
    }

    public function destroyPhoto(User $administrative): RedirectResponse
    {
        $this->authorize('deletePhoto', $administrative);

        if ($this->deleteUserPhoto($administrative)) {
            return redirect()->back()
                ->with('alert-type', 'success')
                ->with('alert-msg', "A fotografia do administrativo {$administrative->name} foi eliminada.");
        }

        return redirect()->back()
            ->with('alert-type', 'warning')
            ->with('alert-msg', "A fotografia do administrativo {$administrative->name} não existe.");
    }

    public function toggleBlock(User $administrative): RedirectResponse
    {
        $this->authorize('toggleBlock', $administrative);

        $administrative->blocked = !$administrative->blocked;
        $administrative->save();

        $message = $administrative->blocked
            ? "O administrativo {$administrative->name} foi bloqueado com sucesso."
            : "O administrativo {$administrative->name} foi desbloqueado com sucesso.";

        return redirect()->back()
            ->with('alert-type', 'success')
            ->with('alert-msg', $message);
    }

    // -------------------------------------------------------------------------
    // GESTÃO DE CLIENTES
    // -------------------------------------------------------------------------

//    public function indexCustomers(Request $request): View
//    {
//        $this->authorize('viewAnyCustomer', User::class);
//
//        $customers = User::where('user_type', 'C')
//            ->when($request->filled('name'), fn ($q) =>
//            $q->where('name', 'like', '%' . $request->name . '%')
//            )
//            ->orderBy('name')
//            ->paginate(20)
//            ->withQueryString();
//
//        return view('customers.index', compact('customers'));
//    }

    public function indexCustomers(Request $request)
    {
        $filterByName = $request->input('name');
        $filterByEmail = $request->input('email');
        $filterByBlocked = $request->input('blocked');

        $query = User::where('user_type', 'C');

        if ($filterByName) {
            $query->where('name', 'like', '%' . $filterByName . '%');
        }

        if ($filterByEmail) {
            $query->where('email', 'like', '%' . $filterByEmail . '%');
        }

        if ($filterByBlocked !== null && $filterByBlocked !== '') {
            $query->where('blocked', $filterByBlocked);
        }

        $customers = $query
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        return view('customers.index', compact(
            'customers',
            'filterByName',
            'filterByEmail',
            'filterByBlocked'
        ));
    }
    public function toggleBlockCustomer(User $customer): RedirectResponse
    {
        $this->authorize('toggleBlockCustomer', $customer);

        $customer->blocked = !$customer->blocked;
        $customer->save();

        $message = $customer->blocked
            ? "O cliente {$customer->name} foi bloqueado com sucesso."
            : "O cliente {$customer->name} foi desbloqueado com sucesso.";

        return redirect()->back()
            ->with('alert-type', 'success')
            ->with('alert-msg', $message);
    }

    public function destroyCustomer(User $customer): RedirectResponse
    {
        $this->authorize('deleteCustomer', $customer);

        $hasOrders = $customer->orders()->exists();
        $hasImages = $customer->tshirtImages()->exists();

        // Soft delete se tiver histórico (enunciado)
        if ($hasOrders || $hasImages) {
            $customer->delete(); // SoftDeletes — preenche deleted_at
            return redirect()->route('customers.index')
                ->with('alert-type', 'success')
                ->with('alert-msg', "O cliente '{$customer->name}' foi removido (os seus dados foram preservados).");
        }

        // Hard delete se não tiver histórico
        $fileName = $customer->photo_url;
        $customer->delete();

        if ($fileName) {
            $this->deletePhotoFile($fileName);
        }

        return redirect()->route('customers.index')
            ->with('alert-type', 'success')
            ->with('alert-msg', "O cliente '{$customer->name}' foi removido com sucesso.");
    }
}
