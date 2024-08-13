@extends('template')


@section('title','Mantenimiento Productos')


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
    if (message) {
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
    }
</script>

@endif
<div class="container-fluid px-4">
    <h1 class="mt-4 text-center">Productos</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active"><a href="{{ route('panel') }}">Inicio</a></li>
        <li class="breadcrumb-item active">Productos</li>
    </ol>
    <div class=" mb-4">
        <a href="{{route('productos.create')}}"><button type="button" class="btn btn-primary">Añadir Nuevo Registro</button></a>
    </div>
    <div class="card mb-4">
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-table me-1"></i>
                Mantenimiento Productos
            </div>
            <div class="card-body">
                <table id="datatablesSimple" class="table table-striped">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Nombre</th>

                            <th>Marca</th>
                            <th>Presentación</th>
                            <th>Categorias</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($productos as $item)
                        <tr>
                            <td>{{$item->codigo}}</td>
                            <td>{{$item->nombre}}</td>
                            <td>{{$item->marca->caracteristica->nombre}}</td>
                            <td>{{$item->presentacione->caracteristica->nombre}}</td>
                            <td>
                                @foreach($item->categorias as $category)
                                <div class="container">
                                    <div class="row">
                                        <span class="m-1 rounded-pill p-1 bg-secondary text-white text-center">{{$category->caracteristica->nombre}}</span>
                                    </div>
                                </div>
                                @endforeach
                            </td>
                            <td>
                                @if($item->estado==1)
                                <span class="fw-bolder p-1 rounded bg-success text-white">Activo</span>
                                @else
                                <span class="fw-bolder p-1 rounded bg-danger text-white">Inactivo</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group" aria-label="Basic mixed styles example">
                                    <form action="{{route('productos.edit',['producto' => $item])}}" method="get">
                                        <button type="submit" class="btn btn-warning">Editar</button>
                                    </form>

                                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#verModal-{{$item->id}}">Visualizar</button>
                                    @if($item->estado==1)
                                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirmModal-{{$item->id}}">Eliminar</button>
                                    @else
                                    <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#confirmModal-{{$item->id}}">Restaurar</button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        <!-- Modal Eliminar-->
                        <div class="modal fade" id="confirmModal-{{$item->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Mensaje de Confirmación</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        {{ $item->estado == 1 ? '¿Seguro que quieres eliminar el producto?' : '¿Seguro que quieres restaurar el producto?' }}
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                        <form action="{{route('productos.destroy',['producto'=>$item->id])}}" method="post">
                                            @method('DELETE')
                                            @csrf
                                            <button type="submit" class="btn btn-primary">Confirmar</button>
                                        </form>

                                    </div>
                                </div>
                            </div>
                        </div>



                         <!-- Modal Visualizar-->
                         <div class="modal fade modal-dialog-scrollable" id="verModal-{{$item->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Detalles del Producto</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row mb-3">
                                            <label><span class="fw-bolder">Descripción: </span> {{$item->descripcion}}</label>
                                        </div>
                                        <div class="row mb-3">
                                            <label><span class="fw-bolder">Fecha de Vencimiento: </span> {{$item->fecha_vencimiento == '' ? 'No tiene' : $item->fecha_vencimiento}}</label>
                                        </div>
                                        <div class="row mb-3">
                                            <label><span class="fw-bolder">Stock: </span> {{$item->stock}}</label>
                                        </div>
                                        <div class="row mb-3">
                                            <label class="fw-bolder">Imagen: </label>
                                            <div>
                                                @if($item->img_path)
                                                <img src="{{Storage::url('public/productos/'.$item->img_path)}}" alt="{{$item->nombre}}" class="img-fluid img-thumbnail border boder-4 rounded ">
                                                @else
                                                <!-- Manejar caso cuando no hay imagen -->
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
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
</div>



@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" type="text/javascript"></script>
<script src="{{ asset('js/datatables-simple-demo.js') }}"></script>
@endpush