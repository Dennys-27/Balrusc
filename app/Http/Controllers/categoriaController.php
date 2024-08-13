<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoriaRequest;
use App\Http\Requests\UpdateCategoriaRequest;
use App\Models\Caracteristica;
use App\Models\Categoria;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\Return_;

class categoriaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //recuperar datos
        $categorias = Categoria::with('caracteristica')->latest()->get();

        return view('categoria.index', ['categorias' => $categorias]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('categoria.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCategoriaRequest $request)
    {
        try {
            // Iniciar la transacción
            DB::beginTransaction();

            // Validar y crear la característica
            $validatedData = $request->validated();
            $caracteristica = Caracteristica::create($validatedData);

            // Crear la categoría asociada
            $caracteristica->categoria()->create([
                'caracteristica_id' => $caracteristica->id,
                // Asegúrate de añadir otros campos necesarios para la categoría
            ]);

            // Confirmar la transacción
            DB::commit();

            // Redirigir con un mensaje de éxito
            return redirect()->route('categorias.index')->with('success', 'Categoria Registrada');
        } catch (Exception $e) {
            // Revertir la transacción en caso de error
            DB::rollBack();

            // Puedes lanzar nuevamente la excepción o manejar el error de otra manera
            // Para depuración, puedes agregar un mensaje de error detallado (opcional)
            return redirect()->route('categorias.index')->with('error', 'Error al registrar la categoria: ' . $e->getMessage());
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
    public function edit(Categoria $categoria)
    {

        //

        return view('categoria.edit', ['categoria' => $categoria]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCategoriaRequest $request, Categoria $categoria)
    {
        Caracteristica::where('id', $categoria->caracteristica->id)
            ->update($request->validated());
        //
        return redirect()->route('categorias.index')->with('success', 'Categoria Editada: ');
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
        $categoria = Categoria::find($id);
        if ($categoria->caracteristica->estado == 1) {
            Caracteristica::where('id', $categoria->caracteristica->id)
                ->update([
                    'estado' => 0
                ]);

            $message = 'Categoria Eliminada';
        } else {
            Caracteristica::where('id', $categoria->caracteristica->id)
                ->update([
                    'estado' => 1
                ]);

            $message = 'Categoria Restaurada';
        }

        return redirect()->route('categorias.index')->with('success', $message);
    }
}
