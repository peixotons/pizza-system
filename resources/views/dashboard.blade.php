@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                @if ($errors->any())
                    <div class="alert alert-danger mt-2">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @if (session('success'))
                    <div class="alert alert-success mt-2">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger mt-2">
                        {{ session('error') }}
                    </div>
                @endif
                <h1 class="text-center mt-2">Pessoas com dívidas</h1>
                <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal"
                        data-bs-target="#addDebtModal">
                    Adicionar Dívida
                </button>
                <table id="debt-table" class="table table-striped">
                    <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Pizzas</th>
                        <th>Refrigerantes</th>
                        <th>Data da dívida</th>
                        <th>Status</th>
                        <th>Foto</th>
                        @if (Auth::user()->user_type === 'administrador')
                            <th>Ações</th>
                        @endif
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($debts as $debt)
                        <tr>
                            <td>{{ $debt->user->name }}</td>
                            <td>{{ $debt->quantity_pizzas }}</td>
                            <td>{{ $debt->quantity_sodas }}</td>
                            <td>{{ \Carbon\Carbon::parse($debt->debt_date)->format('d/m/Y') }}</td>
                            <td class="@if($debt->status == 'pago') text-success @elseif($debt->status == 'pendente') text-danger @endif ">{{ strtoupper($debt->status) }}</td>
                            <td>
                                @if ($debt->photo)
                                    <img class="oval-image" alt="Imagem provando o debito"
                                         src="{{ Storage::url($debt->photo) }}"
                                         width="100">
                                @else
                                    Nenhuma foto
                                @endif
                            </td>
                            @if (Auth::user()->user_type === 'administrador')
                                <td>

                                    <button type="button" class="btn btn-warning" data-bs-toggle="modal"
                                            data-bs-target="#editDebtModal{{ $debt->id }}">Editar
                                    </button>
                                    <button type="button" class="btn btn-secondary" data-bs-toggle="modal"
                                            data-bs-target="#auditDebtModal{{ $debt->id }}">Auditar foto
                                    </button>

                                </td>
                            @endif
                        </tr>

                        <!-- Editar Dívida Modal -->
                        <div class="modal fade" id="editDebtModal{{ $debt->id }}" tabindex="-1"
                             aria-labelledby="editDebtModalLabel{{ $debt->id }}" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('debt.update', $debt->id) }}" method="POST"
                                          enctype="multipart/form-data">
                                        @csrf
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editDebtModalLabel{{ $debt->id }}">Editar
                                                Dívida</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label for="user_id" class="form-label">Nome</label>
                                                <select name="user_id" id="user_id" class="form-select" required>
                                                    <option value="">Selecione um usuário</option>
                                                    @foreach($users as $user)
                                                        <option
                                                            value="{{ $user->id }}" {{ $debt->user_id == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label for="quantity_pizzas" class="form-label">Pizzas</label>
                                                <input type="number" class="form-control" name="quantity_pizzas"
                                                       id="quantity_pizzas" value="{{ $debt->quantity_pizzas }}"
                                                       required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="debt_date" class="form-label">Data da dívida</label>
                                                <input type="date" class="form-control" name="debt_date" id="debt_date"
                                                       value="{{ \Carbon\Carbon::parse($debt->debt_date)->format('Y-m-d') }}"
                                                       required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="status" class="form-label">Status</label>
                                                @if (Auth::user()->user_type === 'administrador')
                                                    <select name="status" id="status" class="form-select" required>
                                                        <option
                                                            value="pendente" {{ $debt->status == 'pendente' ? 'selected' : '' }}>
                                                            Pendente
                                                        </option>
                                                        <option
                                                            value="pago" {{ $debt->status == 'pago' ? 'selected' : '' }}>
                                                            Pago
                                                        </option>
                                                    </select>
                                                @else
                                                    <input type="text" class="form-control" name="status" id="status"
                                                           value="{{ strtoupper($debt->status) }}" readonly>
                                                @endif
                                            </div>
                                            <div class="mb-3">
                                                <label for="photo" class="form-label">Foto</label>
                                                <input type="file" class="form-control" name="photo" id="photo">
                                                @if ($debt->photo)
                                                    <img class="mt-2 oval-image" alt="Imagem provando o debito"
                                                         src="{{ Storage::url($debt->photo) }}" width="100">
                                                @else
                                                    <p class="mt-2">Nenhuma foto</p>
                                                @endif
                                            </div>

                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                    Fechar
                                                </button>
                                                <button type="submit" class="btn btn-primary">Salvar alterações</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>


                        <!-- Auditar Dívida Modal -->
                        <div class="modal fade" id="auditDebtModal{{ $debt->id }}" tabindex="-1"
                             aria-labelledby="auditDebtModalLabel{{ $debt->id }}" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('debt.audit', $debt->id) }}" method="POST"
                                          enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" name="user_id" value="{{ Auth::id() }}">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="auditDebtModalLabel{{ $debt->id }}">Auditar Foto
                                                da Dívida</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label for="user_name" class="form-label">Nome da pessoa:</label>
                                                <span id="user_name">{{ $debt->user->name }}</span>
                                            </div>

                                            <div class="mb-3">
                                                <label for="photo" class="form-label">Foto</label>
                                                @if ($debt->photo)
                                                    <img class="oval-image" alt="Imagem provando o debito"
                                                         src="{{ Storage::url($debt->photo) }}"
                                                         width="100">
                                                @else
                                                    Nenhuma foto
                                                @endif

                                            </div>
                                            <div class="mb-3">
                                                <label for="audit_status" class="form-label">Status da auditoria</label>
                                                <select name="status" id="audit_status" class="form-select" required>
                                                    <option value="pago">Aprovar</option>
                                                    <option value="rejeitar">Rejeitar</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                Fechar
                                            </button>
                                            <button type="submit" class="btn btn-primary">Salvar auditoria</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- Modal para adicionar debito -->
    <div class="modal fade" id="addDebtModal" tabindex="-1" aria-labelledby="addDebtModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addDebtModalLabel">Adicionar Dívida</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('debt.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="user_id" class="form-label">Nome</label>
                            <select name="user_id" id="user_id" class="form-select" required>
                                <option value="">Selecione um usuário</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="quantity_pizzas" class="form-label">Pizzas</label>
                            <input type="number" class="form-control" name="quantity_pizzas" id="quantity_pizzas"
                                   required>
                        </div>
                        <div class="mb-3">
                            <label for="debt_date" class="form-label">Data da dívida</label>
                            <input type="date" class="form-control" name="debt_date" id="debt_date"
                                   value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            @if (Auth::user()->user_type === 'administrador')
                                <select name="status" id="status" class="form-select" required>
                                    <option value="pendente">Pendente</option>
                                    <option value="pago">Pago</option>
                                </select>
                            @else
                                <input type="text" class="form-control" name="status" id="status" value="pendente"
                                       readonly>
                            @endif
                        </div>
                        <div class="mb-3">
                            <label for="photo" class="form-label">Foto</label>
                            <input type="file" class="form-control" name="photo" id="photo">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                        <button type="submit" class="btn btn-primary">Salvar Dívida</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @push('scripts')
        <script>
            $(document).ready(function () {
                $('#debt-table').DataTable();
            });
        </script>
    @endpush
@endsection
