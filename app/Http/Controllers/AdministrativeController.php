<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\AdministrativeFormRequest;

class AdministrativeController extends Controller
{
    use \App\Traits\UserPhotoFileStorage;

    public function index(Request $request): View
    {
        // Correção da coluna para 'user_type' e inclusão de Admins (A) e Funcionários (F)
        $administrativesQuery = User::whereIn('user_type', ['A', 'F'])
            ->orderBy('name');

        $filterByName = $request->query('name');
        if ($filterByName) {
            $administrativesQuery->where('name', 'like', "%$filterByName%");
        }
        $administratives = $administrativesQuery
            ->paginate(20)
            ->withQueryString();

        return view(
            'administratives.index',
            compact('administratives', 'filterByName')
        );
    }

    public function create(): View
    {
        $newAdministrative = new User();
        $newAdministrative->user_type = 'A';
        return view('administratives.create')
            ->with('administrative', $newAdministrative);
    }

    public function store(AdministrativeFormRequest $request): RedirectResponse
    {
        $validatedData = $request->all();
        $newAdministrative = new User();
        $newAdministrative->user_type = 'A';
        $newAdministrative->name = $validatedData['name'];
        $newAdministrative->email = $validatedData['email'];
        $newAdministrative->gender = $validatedData['gender'];
        // A palavra-passe inicial é sempre 123
        $newAdministrative->password = bcrypt('123');
        $newAdministrative->save();
        
        $this->storeUserPhoto($request->photo_file, $newAdministrative);

        $url = route('administratives.show', ['administrative' => $newAdministrative]);
        $htmlMessage = "O administrativo <a href='$url'><u>{$newAdministrative->name}</u></a> foi criado com sucesso!";
        return redirect()->route('administratives.index')
            ->with('alert-type', 'success')
            ->with('alert-msg', $htmlMessage);
    }

    public function edit(User $administrative): View
    {
        return view('administratives.edit')
            ->with('administrative', $administrative);
    }

    public function update(AdministrativeFormRequest $request, User $administrative): RedirectResponse
    {
        $validatedData = $request->all();
        $administrative->user_type = 'A';
        $administrative->name = $validatedData['name'];
        $administrative->email = $validatedData['email'];
        $administrative->gender = $validatedData['gender'];
        $administrative->save();
        if ($request->photo_file) {
            $this->deleteUserPhoto($administrative);
            $this->storeUserPhoto($request->photo_file, $administrative);
        }
        $url = route('administratives.show', ['administrative' => $administrative]);
        $htmlMessage = "O administrativo <a href='$url'><u>{$administrative->name}</u></a> foi atualizado com sucesso!";
        return redirect()->route('administratives.index')
            ->with('alert-type', 'success')
            ->with('alert-msg', $htmlMessage);
    }

    public function destroy(User $administrative): RedirectResponse
    {
        $url = route('administratives.show', ['administrative' => $administrative]);
        
        try {
            $url = route('administratives.show', ['administrative' => $administrative]);
            $fileName = $administrative->photo_url;
            $administrative->delete();
            $this->deletePhotoFile($fileName);
            $alertType = 'success';
            $alertMsg = "O administrativo {$administrative->name} foi eliminado com sucesso!";
            return redirect()->route('administratives.index')
                ->with('alert-type', $alertType)
                ->with('alert-msg', $alertMsg);
        } catch (\Exception $error) {
            $alertType = 'danger';
            $alertMsg = "Não foi possível eliminar o administrativo <a href='$url'><u>{$administrative->name}</u></a> porque ocorreu um erro na operação!";
        }
        return redirect()->back()
            ->with('alert-type', $alertType)
            ->with('alert-msg', $alertMsg);
    }

    public function show(User $administrative): View
    {
        return view('administratives.show')->with('administrative', $administrative);
    }

    public function destroyPhoto(User $administrative): RedirectResponse
    {
        if ($this->deleteUserPhoto($administrative)) {
            return redirect()->back()
                ->with('alert-type', 'success')
                ->with('alert-msg', "A fotografia do administrativo {$administrative->name} foi eliminada.");
        } else {
            return redirect()->back()
                ->with('alert-type', 'warning')
                ->with('alert-msg', "A fotografia do administrativo {$administrative->name} não existe.");
        }
    }

    // BLOQUEAR / DESBLOQUEAR CONTA
    public function toggleBlock(User $administrative): RedirectResponse
    {
        $administrative->blocked = !$administrative->blocked;
        $administrative->save();

        $message = $administrative->blocked
            ? "O administrativo {$administrative->name} foi bloqueado com sucesso."
            : "O administrativo {$administrative->name} foi desbloqueado com sucesso.";

        return redirect()->back()
            ->with('alert-type', 'success')
            ->with('alert-msg', $message);
    }

    // --- GESTÃO DE CLIENTES PELO ADMIN ---
    public function indexCustomers(Request $request): View
    {
        $customersQuery = User::where('user_type', 'C')->orderBy('name');

        $filterByName = $request->query('name');
        if ($filterByName) {
            $customersQuery->where('name', 'like', "%$filterByName%");
        }

        $customers = $customersQuery->paginate(20)->withQueryString();

        return view('customers.index', compact('customers', 'filterByName'));
    }

    public function toggleBlockCustomer(User $customer): RedirectResponse
    {
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
        $hasOrders = false;
        if ($customer->customer()->exists()) {
            $customerProfile = $customer->customer()->first();
            if ($customerProfile && method_exists($customerProfile, 'orders')) {
                $hasOrders = $customerProfile->orders()->exists();
            }
        }

        $hasImages = false;
        if (method_exists($customer, 'tshirtImages')) {
            $hasImages = $customer->tshirtImages()->exists();
        }

        if ($hasOrders || $hasImages) {
            $customer->blocked = true;
            $customer->save();

            return redirect()->route('customers.index')
                ->with('alert-type', 'success')
                ->with('alert-msg', "O cliente '{$customer->name}' foi desativado (soft-delete) para preservar o histórico da plataforma.");
        }

        try {
            $fileName = $customer->photo_url;

            $customer->customer()->delete();

            $customer->delete();

            if ($fileName) {
                $this->deletePhotoFile($fileName);
            }

            return redirect()->route('customers.index')
                ->with('alert-type', 'success')
                ->with('alert-msg', "O cliente '{$customer->name}' foi completamente removido da base de dados.");
        } catch (\Exception $error) {
            $customer->blocked = true;
            $customer->save();

            return redirect()->route('customers.index')
                ->with('alert-type', 'success')
                ->with('alert-msg', "O cliente '{$customer->name}' foi desativado para garantir a integridade da base de dados.");
        }
    }
}