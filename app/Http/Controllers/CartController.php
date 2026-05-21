<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Models\Discipline;
use App\Models\Student;
use App\Http\Requests\CartConfirmationFormRequest;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function show(): View
    {
        $cartOnSession = session('cart', []);
        $cart = Discipline::hydrate($cartOnSession);
        return view('cart.show', compact('cart'));
    }

    public function addToCart(Request $request, Discipline $discipline): RedirectResponse
    {
        $cartOnSession = session('cart', []);
        $cart = Discipline::hydrate($cartOnSession);
        if ($cart->firstWhere('id', $discipline->id)) {
            $alertType = 'warning';
            $url = route('disciplines.show', ['discipline' => $discipline]);
            $htmlMessage = "Discipline <a href='$url'>#{$discipline->id}
                <strong>\"{$discipline->name}\"</strong></a> was not added to the cart
                because it is already included in the cart!";
            return back()
                ->with('alert-msg', $htmlMessage)
                ->with('alert-type', $alertType);
        } else {
            $cart->push($discipline);
        }
        $request->session()->put('cart', $cart->toArray());
        $alertType = 'success';
        $url = route('disciplines.show', ['discipline' => $discipline]);
        $htmlMessage = "Discipline <a href='$url'>#{$discipline->id}
                <strong>\"{$discipline->name}\"</strong></a> was added to the cart.";
        return back()
            ->with('alert-msg', $htmlMessage)
            ->with('alert-type', $alertType);
    }

    public function removeFromCart(Request $request, Discipline $discipline): RedirectResponse
    {
        $url = route('disciplines.show', ['discipline' => $discipline]);
        $cartOnSession = session('cart', []);
        $cart = Discipline::hydrate($cartOnSession);
        $element = $cart->firstWhere('id', $discipline->id);
        if ($element) {
            $cart->forget($cart->search($element));
            if ($cart->count() == 0) {
                $request->session()->forget('cart');
            } else {
                $request->session()->put('cart', $cart->toArray());
            }
            $alertType = 'success';
            $htmlMessage = "Discipline <a href='$url'>#{$discipline->id}
            <strong>\"{$discipline->name}\"</strong></a> was removed from the cart.";
            return back()
                ->with('alert-msg', $htmlMessage)
                ->with('alert-type', $alertType);
        } else {
            $alertType = 'warning';
            $htmlMessage = "Discipline <a href='$url'>#{$discipline->id}
            <strong>\"{$discipline->name}\"</strong></a> was not removed from the cart
            because cart does not include it!";
            return back()
                ->with('alert-msg', $htmlMessage)
                ->with('alert-type', $alertType);
        }
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->session()->forget('cart');
        return back()
            ->with('alert-type', 'success')
            ->with('alert-msg', 'Shopping Cart has been cleared');
    }

    public function confirm(CartConfirmationFormRequest $request): RedirectResponse
    {
        $cartOnSession = session('cart', []);
        $cart = Discipline::hydrate($cartOnSession);
        if (!$cart || ($cart->count() == 0)) {
            return back()
                ->with('alert-type', 'danger')
                ->with('alert-msg', "Cart was not confirmed, because cart is empty!");
        } else {
            $student = Student::where('number', $request->validated()['student_number'])->first();
            if (!$student) {
                return back()
                    ->with('alert-type', 'danger')
                    ->with('alert-msg', "Student number does not exist on the database!");
            }
            $insertDisciplines = [];
            $disciplinesOfStudent = $student->disciplines;
            $ignored = 0;
            foreach ($cart as $discipline) {
                $exist = $disciplinesOfStudent->where('id', $discipline->id)->count();
                if ($exist) {
                    $ignored++;
                } else {
                    $insertDisciplines[$discipline->id] = [
                        "discipline_id" => $discipline->id,
                        "repeating" => 0,
                        "grade" => null,
                    ];
                }
            }
            $ignoredStr = match ($ignored) {
                0 => "",
                1 => "<br>(1 discipline was ignored because student was already enrolled in it)",
                default => "<br>($ignored disciplines were ignored because student was already
                            enrolled on them)"
            };
            $totalInserted = count($insertDisciplines);
            $totalInsertedStr = match ($totalInserted) {
                0 => "",
                1 => "1 discipline registration was added to the student",
                default => "$totalInserted disciplines registrations were added to the student",
            };
            if ($totalInserted == 0) {
                $request->session()->forget('cart');
                return back()
                    ->with('alert-type', 'danger')
                    ->with('alert-msg', "No registration was added to the student!$ignoredStr");
            } else {
                DB::transaction(function () use ($student, $insertDisciplines) {
                    $student->disciplines()->attach($insertDisciplines);
                });
                $request->session()->forget('cart');
                if ($ignored == 0) {
                    return redirect()->route('students.show', ['student' => $student])
                        ->with('alert-type', 'success')
                        ->with('alert-msg', "$totalInsertedStr.");
                } else {
                    return redirect()->route('students.show', ['student' => $student])
                        ->with('alert-type', 'warning')
                        ->with('alert-msg', "$totalInsertedStr. $ignoredStr");
                }
            }
        }
    }
}
