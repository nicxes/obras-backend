<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Models\Budget;

class BudgetController extends Controller
{
    /**
     * Get all budgets
     *
     * @return Response
     */
    public function index(): Response
    {
        $budgets = Budget::all();
        return response($budgets, 200);
    }

    /**
     * Create an budget
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request): Response
    {
        $budget = Budget::create($request->all());
        return response($budget, 201);
    }

    /**
     * Get an budget by id
     *
     * @param int $id
     * @return Response
     */
    public function show(int $id): Response
    {
        $budget = Budget::find($id);
        return response($budget, 200);
    }

    /**
     * Update an budget by id
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, int $id): Response
    {
        $budget = Budget::find($id);
        $budget->update($request->all());
        return response($budget, 200);
    }

    /**
     * Delete an budget by id
     *
     * @param int $id
     * @return Response
     */
    public function destroy(int $id): Response
    {
        $budget = Budget::find($id);
        $budget->delete();
        return response(['message' => 'Budget deleted'], 200);
    }
}