<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMarcaRequest;
use App\Http\Requests\UpdateMarcaRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Exception;
use App\Models\Caracteristica;
use PhpParser\Node\Stmt\Return_;
use App\Models\Marca;
class marcaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //

        $marcas = Marca::with('caracteristica')->latest()->get();
        return view('marca.index', ['marcas' => $marcas]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('marca.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreMarcaRequest $request)
    {
        try {
            // Iniciar la transacción
            DB::beginTransaction();

            // Validar y crear la característica
            $validatedData = $request->validated();
            $caracteristica = Caracteristica::create($validatedData);

            // Crear la categoría asociada
            $caracteristica->marca()->create([
                'caracteristica_id' => $caracteristica->id,
                // Asegúrate de añadir otros campos necesarios para la categoría
            ]);

            // Confirmar la transacción
            DB::commit();

            // Redirigir con un mensaje de éxito
            return redirect()->route('marcas.index')->with('success', 'Marca Registrada');
        } catch (Exception $e) {
            // Revertir la transacción en caso de error
            DB::rollBack();

            // Puedes lanzar nuevamente la excepción o manejar el error de otra manera
            // Para depuración, puedes agregar un mensaje de error detallado (opcional)
            return redirect()->route('marcas.index')->with('error', 'Error al registrar la marca: ' . $e->getMessage());
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
    public function edit(Marca $marca)
    {
        //

        return view('marca.edit', ['marca' => $marca]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateMarcaRequest $request, Marca $marca)
    {
        //
        Caracteristica::where('id',$marca->caracteristica->id)
        ->update($request->validated());

        return redirect()->route('marcas.index')->with('success', 'Marca Editada: ');
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
        $marca = Marca::find($id);
        if ($marca->caracteristica->estado == 1) {
            Caracteristica::where('id', $marca->caracteristica->id)
                ->update([
                    'estado' => 0
                ]);

            $message = 'Marca Eliminada';
        } else {
            Caracteristica::where('id', $marca->caracteristica->id)
                ->update([
                    'estado' => 1
                ]);

            $message = 'Marca Restaurada';
        }

        return redirect()->route('marcas.index')->with('success', $message);
    }
}
