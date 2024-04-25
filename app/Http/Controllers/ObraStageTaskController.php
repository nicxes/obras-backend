<?php

namespace App\Http\Controllers;

use App\Models\ObraStageTask;
use App\Services\ObraStageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ObraStageTaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, int $obraId, int $stageId)
    {
        $obraStageTasks = ObraStageTask::with(['responsible' => function($query){
            $query->select('id', 'firstname', 'lastname');
        }])->where('obra_stage_id', $stageId)->orderBy('start_date', 'asc')->orderBy('id', 'asc')->get();
        return response($obraStageTasks, 200);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
 
        $request->merge(['created_by_id' => auth()->user()->id]);
        $obraStageTask = ObraStageTask::create($request->all());
        return response($obraStageTask, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $obraStageTask = ObraStageTask::with('responsible')->find($id);
        return response($obraStageTask, 200);
    }


    /**
     * Show the form for update the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $obraId, int $stageId, int $id)
    {
        $obraStageTask = ObraStageTask::find($id);
        if (!$obraStageTask) {
            return response()->json(['error' => 'Registro no encontrado'], 404);
        }

        $obraStageTask->update($request->all());
        return response($obraStageTask, 200);
    }


    public function checkCompleted(Request $request, int $obraId, int $stageId, int $id)
    {
        $obraStageTask = ObraStageTask::with('obraStage')->find($id);
        if (!$obraStageTask) {
            return response()->json(['error' => 'Registro no encontrado'], 404);
        }

        $obraStageTask->update(['is_completed' => $request->is_completed]);

        // Obtiene una instancia del servicio ObraStageService
        $obraStageService = app(ObraStageService::class);
        $obraStageService->updateStageProgress($obraStageTask->obraStage);

        $response = [
            'taskIsCompleted' => $obraStageTask->is_completed,
            'stageProgress' => $obraStageTask->obraStage->progress
        ];

        return response($response, 200);
    }
}
