@extends('template')


@section('title','Mantenimiento Proveedor')


@push('css-datatable')
<link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" type="text/css">
@endpush

@push('css')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

@section('content')
@if(session('success'))
<script>
    let message = "{{ session('success') }}";
    const Toast = Swal.mixin({
        toast: true,
        position: "top-end",
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.onmouseenter = Swal.stopTimer;
            toast.onmouseleave = Swal.resumeTimer;
        }
    });
    Toast.fire({
        icon: "success",
        title: message
    });
</script>
@endif
<div class="container-fluid px-4">
    <h1 class="mt-4 text-center">Proveedores</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active"><a href="{{ route('panel') }}">Inicio</a></li>
        <li class="breadcrumb-item active">Proveedores</li>
    </ol>
    <div class=" mb-4">
        <a href="{{route('proveedores.create')}}">
            <button type="button" class="btn btn-primary">Añadir Nuevo Registro</button>
        </a>
    </div>




    <div class="card mb-4">
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-table me-1"></i>
                Mantenimiento Proveedores
            </div>
            <div class="card-body">
                <table id="datatablesSimple" class="table table-striped">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Dirección</th>
                            <th>Documento</th>
                            <th>Tipo de Persona</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($proveedores as $item)
                        <tr>
                            <td>{{$item->persona->razon_social}}</td>
                            <td>{{$item->persona->direccion}}</td>
                            <td>
                                <p class="fw-normal mb-1"> {{$item->persona->documento->tipo_documento}}</p>
                                <p class="text-muted mb-0 text-primary"> {{$item->persona->numero_documento}}</p>

                            </td>
                            <td>{{$item->persona->tipo_persona}}</td>
                            <td>
                                @if($item->persona->estado == 1)
                                <span class="fw-bolder p-1 rounded bg-success text-white">Activo</span>
                                @else
                                <span class="fw-bolder p-1 rounded bg-danger text-white">Inactivo</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group" aria-label="Basic mixed styles example">
                                    <form action="{{route('proveedores.edit',['proveedore'=>$item])}}" method="get">

                                        <button type="submit" class="btn btn-warning">Editar</button>
                                    </form>
                                    @if($item->persona->estado==1)
                                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirmModal-{{$item->id}}">Eliminar</button>
                                    @else
                                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#confirmModal-{{$item->id}}">Restaurar</button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        <!-- Modal -->
                        <div class="modal fade" id="confirmModal-{{$item->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Mensaje de Confirmación</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        {{ $item->persona->estado == 1 ? '¿Seguro que quieres eliminar el Proveedor?' : '¿Seguro que quieres restaurar el Proveedor?' }}
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                        <form action="{{route('proveedores.destroy',['proveedore'=>$item->persona->id])}}" method="post">
                                            @method('DELETE')
                                            @csrf
                                            <button type="submit" class="btn btn-primary">Confirmar</button>
                                        </form>

                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </tbody>


                </table>
            </div>
        </div>
    </div>
    @endsection


    @push('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
    <script src="{{ asset('assets/demo/chart-area-demo.js')}}"></script>

    <script src="{{ asset('assets/demo/chart-bar-demo.js')}}"></script>

    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
    <script src="{{ asset('js/datatables-simple-demo.js')}}"></script>
    @endpush