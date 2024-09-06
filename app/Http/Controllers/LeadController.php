<?php

namespace App\Http\Controllers;

use App\Http\Requests\LeadRequest;
use App\Models\Lead;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class LeadController extends Controller
{
    public function store(LeadRequest $request): JsonResponse
    {
        $lead = new Lead();
        $lead->first_name = $request->post('first_name');
        $lead->last_name = $request->post('last_name');
        $lead->email = $request->post('email');
        $lead->phone = $request->post('phone');
        $lead->message = $request->post('message');
        $lead->status = Lead::STATUS_NEW;
        $lead->created_at = Carbon::now();
        $lead->save();

        return new JsonResponse([], ResponseAlias::HTTP_OK);
    }


    public function destroy(Request $request): JsonResponse
    {
        Lead::query()
            ->where('id', $request->post('lead_id'))
            ->delete();

        $leads = Lead::all();

        return new JsonResponse(['leads' => $leads], ResponseAlias::HTTP_OK);
    }

    public function edit(Request $request): JsonResponse
    {
        $lead = Lead::query()->find($request->post('id'));
        $lead->first_name = $request->post('first_name');
        $lead->last_name = $request->post('last_name');
        $lead->email = $request->post('email');
        $lead->phone = $request->post('phone');
        $lead->message = $request->post('message');
        $lead->updated_at = Carbon::now();
        $lead->save();

        $leads = Lead::all();

        return new JsonResponse(['leads' => $leads], ResponseAlias::HTTP_OK);
    }

    public function editStatus(Request $request): JsonResponse
    {
        $lead = Lead::query()->find($request->post('id'));
        $lead->status = $request->post('status');
        $lead->updated_at = Carbon::now();
        $lead->save();

        return new JsonResponse([], ResponseAlias::HTTP_OK);
    }
}
