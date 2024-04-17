<?php

namespace App\Http\Controllers;

use App\Http\Requests\Client\CreateClientRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Models\Client;

class ClientController extends Controller
{
    /**
     * Get all clients
     *
     * @return Response
     */
    public function index(): Response
    {
        $clients = Client::all();
        return response($clients, 200);
    }

    /**
     * Create an client
     *
     * @param Request $request
     * @return Response
     */
    public function store(CreateClientRequest $request): Response
    {
        $client = Client::create($request->all());
        return response($client, 201);
    }

    /**
     * Get an client by id
     *
     * @param int $id
     * @return Response
     */
    public function show(int $id): Response
    {
        $client = Client::with('users')->find($id);
        return response($client, 200);
    }

    /**
     * Update an client by id
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(CreateClientRequest $request, int $id): Response
    {
        $client = Client::find($id);
        $client->update($request->all());
        return response($client, 200);
    }

    /**
     * Delete an client by id
     *
     * @param int $id
     * @return Response
     */
    public function destroy(int $id): Response
    {
        $client = Client::find($id);
        $client->delete();
        return response(['message' => 'Client deleted'], 200);
    }
}
