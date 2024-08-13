<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePersonaRequest;
use App\Http\Requests\UpdateProveedoreRequest;
use App\Models\Cliente;
use App\Models\Documento;
use App\Models\Persona;
use App\Models\Proveedore;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use function PHPUnit\Framework\returnSelf;

class proveedoreController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $proveedores = Proveedore::with('persona.documento')->get();

        return view('proveedore.index', compact('proveedores'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $documentos = Documento::all();
        return view('proveedore.create', compact('documentos'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePersonaRequest $request)
    {
        try {
            DB::beginTransaction();
            $persona = Persona::create($request->validated());
            $persona->proveedore()->create([
                'persona_id' => $persona->id
            ]);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }

        return redirect()->route('proveedores.index')->with('success', 'Proveedor Registrado');
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
    public function edit(Proveedore $proveedore)
    {
        //
        $proveedore->load('persona.documento');
        $documentos = Documento::all();
        return view('proveedore.edit', compact('proveedore', 'documentos'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProveedoreRequest  $request, Proveedore $proveedore)
    {
        //

        try {
            DB::beginTransaction();
            Persona::where('id', $proveedore->persona->id)
                ->update($request->validated());
            DB::commit();
            //code...
        } catch (Exception $e) {
            //throw $th;
            DB::rollBack();
        }
        return redirect()->route('proveedores.index')->with('success', 'Proveedor Editado');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(string $id)
    {
        $message = '';
        $persona = Persona::find($id);

        if ($persona->estado == 1) {
            Persona::where('id', $persona->id)
                ->update([
                    'estado' => 0
                ]);
            # code...
            $message = 'Proveedor eliminado';
        } else {

            Persona::where('id', $persona->id)
                ->update([
                    'estado' => 1
                ]);
            # code...
            $message = 'Proveedor Restaurado';
        }




        return redirect()->route('proveedores.index')->with('success', $message);
    }
}
