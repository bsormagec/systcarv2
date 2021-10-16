<?php

namespace Augusto\Appointments\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\tenant\Appointment;

class AppoinmentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $events = Appointment::with('customer:id,fullName,ci')
                                ->filter($request->query())
                                ->get([
                                    'id','fecha','descriptions as title', 'start_at as start', 
                                    'finish_at as end','customer_id','status'
                                ]);

        return response()->json($events);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validation = Appointment::getModel()->validate($request->all(), 'create');

        if ($validation->passes())
        {
            $consulta = Appointment::create([
                'descriptions' => $request->title,
                'fecha' => $request->fecha,
                'start_at' => $request->start,
                'finish_at' => $request->end,
                'status' => $request->status,
                'user_id' => auth()->user()->id,
                'customer_id' => $request->customer['id'],
                'sucursal_id' => session()->get('sucursal')
            ]);

            if ($consulta)
            {
                return response()->json([
                    'success' => true,
                    'event' => $consulta
                ]);
            }
        }

        return response()->json([
            'error' => true,
            'message' => $validation->errors()->first()
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $event = Appointment::findOrFail($id);
        $validation = Appointment::getModel()->validate($request->input(), 'update');

        if ($validation->passes())
        {
            $event->update([
                'descriptions' => $request->title,
                'fecha' => $request->fecha,
                'start_at' => $request->start,
                'finish_at' => $request->end,
                'status' => $request->status,
                'customer_id' => $request->customer['id'],
            ]);

            return response()->json([
                'success' => true,
                'event' => $event
            ]);
        }

        return response()->json([
            'error' => true,
            'message' => $validation->errors()->first()
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $event = Appointment::findOrFail($id);

        if ( ! is_null($event))
        {
            //actualizamos su estado a anulado
            $event->update([
                'status' => 'A'
            ]);
            // procedemos a la eliminacion de la cita
            $event->delete();

            return response()->json(['success' => true]);
        }

        return response()->json(['error' => true]);
    }
}
