@extends('layout.master')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <form class="form-inline" id="form-filter">
                        {{-- filter Role --}}
                        <div class="form-group">
                            <label for="role">Role</label>
                            <div class="col-3">
                                <select class="form-control select-filter" name="role" id="role">
                                    <option selected>All</option>
                                    @foreach ($roles as $role => $value)
                                        <option value="{{ $value }}"
                                            @if ((string) $value === $selectedRole) selected @endif>
                                            {{ $role }}
                                        </option>
                                    @endforeach

                                </select>
                            </div>
                        </div>
                        {{-- filter City --}}
                        <div class="form-group">
                            <label for="city">City</label>
                            <div class="col-3">
                                <select class="form-control select-filter" name="city" id="city">
                                    <option selected>All</option>
                                    @foreach ($cities as $city)
                                        <option value="{{ $city }}"
                                            @if ($city === $selectedCity) selected @endif>
                                            {{ $city }}
                                        </option>
                                    @endforeach

                                </select>
                            </div>
                        </div>
                        {{-- filter Company --}}
                        <div class="form-group">
                            <label for="company">City</label>
                            <div class="col-3">
                                <select class="form-control select-filter" name="company" id="company">
                                    <option selected>All</option>
                                    @foreach ($companies as $company)
                                        <option value="{{ $company->id }}"
                                            @if ($company->id == $selectedCompany) selected @endif>
                                            {{ $company->name }}
                                        </option>
                                    @endforeach

                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="company">Display</label>
                            <div class="col-3">
                                <select class="form-control select-filter" name="display" id="display">
                                    @foreach ($displayNumbers as $displayNumber)
                                        <option value="{{ $displayNumber }}"
                                            @if ($displayNumber == $selectNumberDisplay) selected @endif>
                                            {{ $displayNumber }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="card-body">
                    <div id="products-datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                        <div class="row">
                            <div class="col-sm-12">
                                <table class="table table-hover table-centered mb-0">
                                    <thead>
                                        <tr role="row">
                                            <th>#</th>
                                            <th>Avatar</th>
                                            <th>Infor</th>
                                            <th>Role</th>
                                            <th>Position</th>
                                            <th>City</th>
                                            <th>Company</th>
                                            <th>Delete</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data as $each)
                                            <tr role="row">
                                                <td>
                                                    <a href="{{ route("admin.$table.show", $each) }}">
                                                        {{ $each->id }}
                                                    </a>
                                                </td>
                                                <td>
                                                    <img src="{{ $each->avatar }}" height="30">
                                                </td>
                                                <td>
                                                    {{ $each->name }} - {{ $each->gender_name }}
                                                    <br>
                                                    <a href="mailto:{{ $each->email }}">
                                                        {{ $each->email }}
                                                    </a>
                                                    <br>
                                                    <a href="tel:{{ $each->phone }}">
                                                        {{ $each->phone }}
                                                    </a>
                                                </td>
                                                <td>
                                                    {{ $each->role_name }}
                                                </td>
                                                <td>
                                                    {{ $each->position }}
                                                </td>
                                                <td>
                                                    {{ $each->city }}
                                                </td>
                                                <td>
                                                    {{ $each->company_name }}
                                                </td>
                                                <td>
                                                    <form action="{{ route("admin.$table.destroy", $each) }}"
                                                        method="post">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="bnt btn-danger">Delete</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- Center Align -->
                    <div class="row mt-3">
                        <div class="col-sm-12">
                            <nav aria-label="Page navigation example">
                                <ul class="pagination justify-content-center">
                                    {{ $data->links() }}
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
@push('js')
    <script>
        $(document).ready(function() {
            $(".select-filter").change(function() {
                $("#form-filter").submit();
            });
        });
    </script>
@endpush
