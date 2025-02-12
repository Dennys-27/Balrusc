@extends('template')


@section('title','Crear Marca')


@push('css')
<style>

</style>
@endpush
@section('content')



<div class="container-fluid px-4">
    <h1 class="mt-4 text-center">Editar Marca</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active"><a href="{{ route('panel') }}">Inicio</a></li>
        <li class="breadcrumb-item active"><a href="{{ route('marcas.index') }}">Marca</a></li>
        <li class="breadcrumb-item active">Editar Marca</li>
    </ol>
    <div class="container w-100 border border-3 border-primary rounded p-4 mt-3">
        <form action="{{route('marcas.update',['marca'=>$marca])}}" method="post">
            <!-- Nos permite enviar Formulario -->
            @method('PATCH')
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="nombre">Nombre:</label>
                    <input type="text" name="nombre" id="nombre" class="form-control" value="{{old('nombre',$marca->caracteristica->nombre)}}">
                    @error('nombre')
                    <small class="text-danger">{{$message}}</small>
                    @enderror
                </div>
                <div class="col-md-12">
                    <label for="descripcion">Descripción:</label>
                    <textarea name="descripcion" id="descripcion" rows="3" class="form-control">{{old('descripcion',$marca->caracteristica->descripcion)}}</textarea>
                    @error('descripcion')
                    <small class="text-danger">{{$message}}</small>
                    @enderror
                </div>
                <div class="col-md-12 text-center">
                    <button type="submit" class="btn btn-primary">Actualizar</button>
                    <button type="reset" class="btn btn-secondary">Reiniciar</button>

                </div>
        </form>
    </div>

</div>
@endsection

@push('js')

@endpush