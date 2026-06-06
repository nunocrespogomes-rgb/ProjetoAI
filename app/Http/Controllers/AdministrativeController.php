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
        // Initial password is always 123
        $newAdministrative->password = bcrypt('123');
        $newAdministrative->save();
        // File store is the last thing to execute!
        // Files do not rollback, so the probability of having a pending file
        // (not referenced by any user) is reduced by being the last operation
        $this->storeUserPhoto($request->photo_file, $newAdministrative);

        $url = route('administratives.show', ['administrative' => $newAdministrative]);
        $htmlMessage = "Administrative <a href='$url'><u>{$newAdministrative->name}</u></a> has been created successfully!";
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
        $htmlMessage = "Administrative <a href='$url'><u>{$administrative->name}</u></a> has been updated successfully!";
        return redirect()->route('administratives.index')
            ->with('alert-type', 'success')
            ->with('alert-msg', $htmlMessage);
    }

    public function destroy(User $administrative): RedirectResponse
    {
        try {
            $url = route('administratives.show', ['administrative' => $administrative]);
            $fileName = $administrative->photo_url;
            $administrative->delete();
            $this->deletePhotoFile($fileName);
            $alertType = 'success';
            $alertMsg = "Administrative {$administrative->name} has been deleted successfully!";
            return redirect()->route('administratives.index')
                ->with('alert-type', $alertType)
                ->with('alert-msg', $alertMsg);
        } catch (\Exception $error) {
            $alertType = 'danger';
            $alertMsg = "It was not possible to delete the administrative
                            <a href='$url'><u>{$administrative->name}</u></a>
                            because there was an error with the operation!";
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
                ->with('alert-msg', "Photo of administrative {$administrative->name} has been deleted.");
        } else {
            return redirect()->back()
                ->with('alert-type', 'warning')
                ->with('alert-msg', "Photo of administrative {$administrative->name} does not exist.");
        }
    }

    //BLOQUEAR / DESBLOQUEAR CONTA
    public function toggleBlock(User $administrative): RedirectResponse
    {
        // Inverte o estado atual do campo blocked (se estiver 0 vira 1, se for 1 vira 0)
        $administrative->blocked = !$administrative->blocked;
        $administrative->save();

        $message = $administrative->blocked
            ? "Administrative {$administrative->name} has been blocked successfully."
            : "Administrative {$administrative->name} has been unblocked successfully.";

        return redirect()->back()
            ->with('alert-type', 'success')
            ->with('alert-msg', $message);
    }

    // --- GESTÃO DE CLIENTES PELO ADMIN ---
    public function indexCustomers(Request $request): View
    {
        // Procura apenas utilizadores cujo user_type seja 'C' (Clientes)
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
            ? "Customer {$customer->name} has been blocked successfully."
            : "Customer {$customer->name} has been unblocked successfully.";

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
                ->with('alert-msg', "Customer '{$customer->name}' has been soft-deleted (disabled) to preserve platform history.");
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
                ->with('alert-msg', "Customer '{$customer->name}' has been completely removed from the database.");
        } catch (\Exception $error) {
            $customer->blocked = true;
            $customer->save();

            return redirect()->route('customers.index')
                ->with('alert-type', 'success')
                ->with('alert-msg', "Customer '{$customer->name}' has been soft-deleted to guarantee database integrity.");
        }
    }
}
