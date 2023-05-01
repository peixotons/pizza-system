<?php

namespace App\Http\Controllers;

use App\Models\PizzaDebt;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class DebtController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => [
                'required',
                Rule::exists('users', 'id'),
            ],
            'quantity_pizzas' => 'required|integer|min:1',
            'debt_date' => 'required|date',
            'status' => 'required|in:pendente,pago',
            'photo' => 'required|image|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('public/photos');
            $validated['photo'] = $photoPath;
        }

        $validated['quantity_sodas'] = intval($request->input('quantity_pizzas') / 3);

        $createdDebt = PizzaDebt::create($validated);

        // Verifique se a quantidade total de pizzas do usuário é um múltiplo de 3
        $userTotalPizzas = PizzaDebt::where('user_id', $createdDebt->user_id)
            ->where('status', 'pendente')
            ->sum('quantity_pizzas');

        if ($userTotalPizzas % 3 == 0) {
            $this->addSodaToUser($createdDebt->user_id, $createdDebt->id);
        }

        return redirect()->route('dashboard.page')->with('success', 'Dívida adicionada com sucesso!');
    }

    public function audit(Request $request, $id)
    {
        $validated = $request->validate([
            'user_id' => 'required|integer|exists:users,id',
        ]);

        $debt = PizzaDebt::findOrFail($id);

        $audit_status = $request->input('status');

        if ($audit_status === 'rejeitar') {
            $debt->delete();
            return redirect()->route('dashboard.page')->with('success', 'Dívida rejeitada e excluída com sucesso!');
        } else {
            $debt->status = 'pago';
        }

        $debt->user_id = $validated['user_id'];
        $debt->save();

        return redirect()->route('dashboard.page')->with('success', 'Dívida auditada com sucesso!');
    }

    public function update(Request $request, $id)
    {
        $debt = PizzaDebt::findOrFail($id);

        $validated = $request->validate([
            'user_id' => 'required',
            'quantity_pizzas' => 'required|integer',
            'debt_date' => 'required|date',
            'status' => 'required|in:pendente,pago',
            'photo' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('public/debts');
            $debt->photo = $path;
        }

        $debt->user_id = $validated['user_id'];
        $debt->quantity_pizzas = $validated['quantity_pizzas'];
        $debt->debt_date = $validated['debt_date'];
        $debt->status = $validated['status'];

        $debt->save();

        return redirect()->route('dashboard.page')->with('success', 'Dívida atualizada com sucesso!');
    }

    public function addSodaToUser($userId, $debtId)
    {
        $debt = PizzaDebt::where('user_id', $userId)->where('id', $debtId)->first();

        if ($debt) {
            $debt->quantity_sodas += 1;
            $debt->save();
        }
    }

}
