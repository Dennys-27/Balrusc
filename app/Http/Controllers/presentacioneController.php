<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePresentacioneRequest;
use App\Http\Requests\UpdatePresentacioneRequest;
use App\Models\Caracteristica;
use Illuminate\Support\Facades\DB;
use App\Models\Presentacione;
use Illuminate\Http\Request;
use Exception;

class presentacioneController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $presentaciones = Presentacione::with('caracteristica')->latest()->get();
        return view('presentacione.index', ['presentaciones' => $presentaciones]);
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('presentacione.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePresentacioneRequest $request)
    {
        try {
            //Inicia la transaccion
            DB::beginTransaction();
            $validatedData = $request->validated();
            $caracteristica = Caracteristica::create($validatedData);

            // Crear la categoría asociada
            $caracteristica->presentacione()->create([
                'caracteristica_id' => $caracteristica->id,
                // Asegúrate de añadir otros campos necesarios para la categoría
            ]);

            // Confirmar la transacción
            DB::commit();
            return redirect()->route('presentaciones.index')->with('success', 'Presentación Registrada');
        } catch (Exception $e) {
            // Revertir la transacción en caso de error
            DB::rollBack();

            // Puedes lanzar nuevamente la excepción o manejar el error de otra manera
            // Para depuración, puedes agregar un mensaje de error detallado (opcional)
            return redirect()->route('presentaciones.index')->with('error', 'Error al registrar la Presentacion: ' . $e->getMessage());
        }
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Presentacione $presentacione)
    {
        //

        return view('presentacione.edit', ['presentacione' => $presentacione]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePresentacioneRequest $request, Presentacione $presentacione)
    {
        //

        Caracteristica::where('id',$presentacione->caracteristica->id)
        ->update($request->validated());

        return redirect()->route('presentaciones.index')->with('success','Presentacion Eliminada');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(string $id)
    {
        //
        $message = '';
        $presentacione = Presentacione::find($id);
        if ($presentacione->caracteristica->estado == 1) {
            Caracteristica::where('id', $presentacione->caracteristica->id)
                ->update([
                    'estado' => 0
                ]);

            $message = 'Presentacion Eliminada';
        } else {
            Caracteristica::where('id', $presentacione->caracteristica->id)
                ->update([
                    'estado' => 1
                ]);

            $message = 'Presentacion Restaurada';
        }

        return redirect()->route('presentaciones.index')->with('success', $message);
    }
}