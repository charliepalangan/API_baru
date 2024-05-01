<?php

namespace App\Http\Controllers;

use App\Http\Requests\request_customer;
use Illuminate\Http\Request;
use App\Models\model_customer;
use App\Http\Resources\resource_customer;
use App\Http\Resources\resource_gaji;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\resource_pesanan;
use App\Services\service_pesanan;

class controller_customer extends Controller
{
    private service_pesanan $service;

    public function createCustomer(request_customer $request)
    {
        $validated = $request->validated();

        $customer = model_customer::create($validated);
        return new resource_customer($customer);
    }

   public function updateCustomer(request_customer $request, $id)
{
    try {
        $customer = model_customer::where('Email', $id)->firstOrFail();
        $validated = $request->validated();

        $customer->update($validated);
        return new resource_customer($customer);
    } catch (ModelNotFoundException $exception) {
        return response()->json([
            'message' => 'Customer dengan email ' . $id . ' tidak ditemukan',
        ], 404);
    } catch (\Throwable $th) {
        return response()->json([
            'message' => 'Terjadi kesalahan dalam pembaruan customer',
        ], 500);
    }
}

    public function readCustomer()
    {
        $customer = model_customer::all();
        return resource_customer::collection($customer);
    }

    public function getById(string $id)
    {
        $customer = model_customer::where('Email', $id)->first();

        if (!$customer) {
            return response()->json([
                'message' => "Customer dengan ID $id tidak ditemukan."
            ], 404);
        }

        return new resource_customer($customer);
    }

    public function getByNama(string $name)
    {
        $customer = model_customer::where('Nama', $name)->first();

        if (!$customer) {
            return response()->json([
                'message' => "Customer dengan nama '$name' tidak ditemukan."
            ], 404);
        }

        return new resource_customer($customer);
    }

    public function registerCustomer(request_customer $request)
    {
        $validated = $request->validated();
        try {
            $validated['Password'] = password_hash($validated['Password'], PASSWORD_DEFAULT);
            $customer = model_customer::create($validated);
            return new resource_customer($customer);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

   
    public function __construct(service_pesanan $service)
    {
        $this->service = $service;
    }

    public function getHistoryByEmail(string $id)
    {
        $pesanan = $this->service->readHistoryByEmail($id);

        return  resource_pesanan::collection($pesanan);
    }
}
