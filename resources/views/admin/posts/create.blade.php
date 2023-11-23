@extends('layout.master')
@push('css')
    <link rel="stylesheet" href="{{ asset('css/summernote-bs4.css') }}">
    <style>
        .error {
            color: red !important;
        }

        .note-btn-group .btn-light {
            color: #3688fc;
        }

        input[data-switch]:checked+label:after {
            left: 90px;
        }

        input[data-switch]+label {
            width: 110px;
        }
    </style>
@endpush
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div id="div_errors" class="alert alert-danger d-none">
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.posts.store') }}" method="POST" class="form-horizontal"
                        id="form-create-post">
                        @csrf
                        <div class="form-group">
                            <label>Compayny</label>
                            <select class="form-control" name="company" id="select-company"></select>
                        </div>
                        <div class="form-group">
                            <label>Language (*)</label>
                            <select class="form-control" multiple name="languages[]" id="select-language"></select>
                        </div>
                        <div class="form-row select-location">
                            <div class="form-group col-md-6">
                                <label>City (*)</label>
                                <select class="form-control select-city" name="city" id="select-city"></select>
                            </div>
                            <div class="form-group col-md-6">
                                <label>District</label>
                                <select class="form-control select-district" name="district" id="select-district"></select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-4">
                                <label>Min Salary</label>
                                <input type="text" name="min_salary" data-toggle="touchspin">
                            </div>
                            <div class="form-group col-4">
                                <label>Max Salary</label>
                                <input type="text" name="max_salary" data-toggle="touchspin"
                                    data-bts-max="1000000000000">
                            </div>
                            <div class="form-group col-4">
                                <label>Currency Salary</label>
                                <select name="currency_salary" class="form-control">
                                    @foreach ($currencies as $currency => $value)
                                        <option value="{{ $value }}">
                                            {{ $currency }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-8">
                                <label>Requirement</label>
                                <textarea name="requirement" id="text-requirement"></textarea>
                            </div>
                            <div class="form-group col-4">
                                <label>Number Applicants</label>
                                <input type="text" name="number_applicants" data-toggle="touchspin">
                                <br>
                                <label>Remotable</label>
                                <select name="remotable" class="form-control">
                                    @foreach ($remotables as $key => $val)
                                        <option value="{{ $val }}">
                                            {{ __('frontpage.' . strtolower($key)) }}
                                        </option>
                                    @endforeach
                                </select>
                                <br>
                                <input type="checkbox" name="can_parttime" id="can_parttime" checked data-switch="info">
                                <label for="can_parttime" data-on-label="Can Part-time"
                                    data-off-label="No Part-time"></label>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-6">
                                <label>Start Date</label>
                                <input type="text" name="start_date" class="form-control date" id="birthdatepicker"
                                    data-toggle="date-picker" data-single-date-picker="true">
                            </div>
                            <div class="form-group col-6">
                                <label>End Date</label>
                                <input type="text" name="end_date" class="form-control date" id="birthdatepicker"
                                    data-toggle="date-picker" data-single-date-picker="true">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-6">
                                <label>Title</label>
                                <input type="text" name="job_tittle" id="title" class="form-control">
                            </div>
                            <div class="form-group col-6">
                                <label>Slug</label>
                                <input type="text" name="slug" id="slug" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <button id="btn-submit" disabled class="btn btn-outline-success btn-rounded"><i
                                    class="uil-cloud-computing"></i> Create</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    {{-- Modal create company --}}
    <div id="modal-company" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Create Company</h4>
                    <button type="button" class="close float-right" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form id="form-create-company" class="form-horizontal" action="{{ route('admin.companies.store') }}"
                        method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label>Compayny</label>
                            <input readonly name="name" id="company" class="form-control">
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>Address</label>
                                <input type="text" name="address" class="form-control">
                            </div>
                            <div class="form-group col-md-6">
                                <label>Address2</label>
                                <input type="text" name="address2" class="form-control">
                            </div>
                        </div>
                        <div class="form-row select-location">
                            <div class="form-group col-md-4">
                                <label>Country</label>
                                <select class="form-control" name="country" id="country">
                                    @foreach ($countries as $val => $name)
                                        <option value="{{ $val }}">
                                            {{ $name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label>City</label>
                                <select class="form-control select-city" name="city" id="city"></select>
                            </div>
                            <div class="form-group col-md-4">
                                <label>District</label>
                                <select class="form-control select-district" name="district" id="district"></select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>Zipcode</label>
                                <input type="number" name="zipcode" class="form-control">
                            </div>
                            <div class="form-group col-md-6">
                                <label>Phone</label>
                                <input type="number" name="phone" class="form-control">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>Email</label>
                                <input type="email" name="email" class="form-control">
                            </div>
                            <div class="form-group col-md-6">
                                <label>Logo</label>
                                <input type="file" name="logo"
                                    oninput="pic.src=window.URL.createObjectURL(this.files[0])">
                                <img id="pic" height="100" />
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" onclick="submitForm('company')" class="btn btn-success">Create</button>
                </div>
            </div>

        </div>
    </div>
@endsection
@push('js')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.js"></script>
    <script src="{{ asset('js/summernote-bs4.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        let checkChangeTitle = false;

        function generateTitle() {
            let languages = [];
            $("#select-language :selected").map(function(i, v) {
                languages.push($(v).text());
            });
            languages = languages.join(',');
            const city = $("#select-city").val();
            const company = $("#select-company").val();

            let resultTitle = `(${city}) ${languages}`;
            if (company) {
                resultTitle += ' - ' + company;
            }
            return resultTitle;
        }

        function generateSlug(title) {
            $.ajax({
                url: "{{ route('api.posts.slug.generate') }}",
                type: 'GET',
                data: {
                    title
                },
                dataType: "json",
                success: function(response) {
                    $("#slug").val(response.data);
                    $("#slug").trigger("change");
                },
                error: function() {

                }
            });
        }

        async function loadDistrict(parent) {
            // remove old district  
            parent.find(".select-district").empty();
            const path = parent.find(".select-city option:selected").data('path');
            if (!path) {
                $("#select-district").append(`
                    <option data-path='null'>
                        Select District
                    </option>`);
            } else {
                // get data district with city path from local file
                const response = await fetch('{{ asset('locations/') }}' + path);
                const districts = await response.json();
                let string = '';
                const selectedValue = $("#select-district").val();
                $.each(districts.district, function(index, each) {
                    let nameDistrict = (each.pre ? each.pre + ' ' : '') + each.name;
                    string += `<option`;
                    if (selectedValue === nameDistrict) {
                        string += ` selected `;
                    }
                    string += `>${nameDistrict}</option>`;
                })
                parent.find(".select-district").append(string);
            }
        }

        function checkExitsCompany() {

            $.ajax({
                url: '{{ route('api.companies.check') }}/' + $("#select-company").val(),
                type: 'GET',
                dataType: "json",
                success: function(response) {
                    if (response.data) {
                        // check company exists
                        submitForm('post');
                    } else {
                        // show modal create company
                        $("#modal-company").modal("show");
                        $("#company").val($("#select-company").val());
                        $("#city").val($("#select-city").val()).trigger('change');
                    }
                },
            });
        }

        function showError(errors) {
            let string = '<ul>'
            if (Array.isArray(errors)) {
                errors.forEach(function(each) {
                    each.forEach(function(error) {
                        string += `<li>${error}</li>`;
                    });
                });
            } else {
                string += `<li>${errors}</li>`;
            }
            string += '</ul>';
            $("#div_errors").html(string);
            $("#div_errors").removeClass("d-none").show();
            notifyError(string);
        }

        function submitForm(type) {
            const obj = $("#form-create-" + type);
            const formData = new FormData(obj[0]);
            $.ajax({
                url: obj.attr('action'),
                type: 'POST',
                dataType: "json",
                data: formData,
                processData: false,
                async: false,
                cache: false,
                contentType: false,
                enctype: 'multipart/form-data',
                success: function(response) {
                    if (response.success) {
                        $("#div_errors").hide();
                        $("#modal-company").modal("hide");
                        notifySuccess();
                    } else {
                        showError(response.message);
                    }
                },
                error: function(response) {
                    let errors;
                    if (response.responseJSON.errors) {
                        errors = Object.values(response.responseJSON.errors);
                        showError(errors);
                    } else {
                        errors = response.responseJSON.message;
                        showError(errors);
                    }

                },
            });
        }

        $(document).ready(async function() {
            $("#text-requirement").summernote("foreColor", "blue");
            $("#select-city").select2({
                tags: true
            });
            $("#city").select2({
                tags: true
            });
            const response = await fetch('{{ asset('locations/index.json') }}');
            const cities = await response.json();

            // set up first time city and district
            $("#select-city").append(`
                <option data-path='null'>
                   Select City
                </option>`)
            $("#select-district").append(`
                <option data-path='null'>
                   Select District
                </option>`)

            // fill data to city
            $.each(cities, function(index, each) {
                $("#select-city").append(`
                <option data-path='${each.file_path}'>
                    ${index}
                </option>`)

                $("#city").append(`
                <option data-path='${each.file_path}'>
                    ${index}
                </option>`)
            })

            // ------- catch event -------
            $("#select-city, #city").change(function(e) {
                loadDistrict($(this).parents('.select-location'));
            });
            $("#select-district").select2({
                tags: true,
            });
            $("#district").select2({
                tags: true,
            });
            await loadDistrict($('#select-city').parents('.select-location'));

            $("#select-company").select2({
                tags: true,
                ajax: {
                    delay: 250,
                    url: '{{ route('api.companies') }}',
                    // Search query parameters
                    data: function(params) {
                        var queryParameters = {
                            q: params.term
                        }

                        return queryParameters;
                    },
                    // crawl data from api
                    processResults: function(data) {
                        return {
                            results: $.map(data.data, function(item) {
                                return {
                                    text: item.name,
                                    id: item.name,
                                }
                            })
                        };
                    },
                }
            });

            $("#select-language").select2({
                ajax: {
                    delay: 250,
                    url: '{{ route('api.languages') }}',
                    // Search query parameters
                    data: function(params) {
                        var queryParameters = {
                            q: params.term
                        }

                        return queryParameters;
                    },
                    // crawl data from api
                    processResults: function(data) {
                        return {
                            results: $.map(data.data, function(item) {
                                return {
                                    text: item.name,
                                    id: item.id,
                                }
                            })
                        };
                    },
                }
            });
            $(document).on('change', '#select-language, #select-company, #select-city', function() {
                let result = generateTitle();
                if (checkChangeTitle == false) {
                    $("#title").val(result);
                }
                generateSlug(result);
            })

            $("#slug").change(function() {
                $("#btn-submit").attr('disabled', true);
                if ($(this).val().includes("select-city") || $("#select-language").val().length == 0 ||
                    $("#select-company").val() == null) {
                    return;
                }
                $.ajax({
                    url: "{{ route('api.posts.slug.check') }}",
                    type: 'POST',
                    data: {
                        slug: $(this).val(),
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response.success) {
                            $("#btn-submit").attr('disabled', false);
                        }
                    }
                });
            });

            $("#title").change(function() {
                if (checkChangeTitle) return;
                let userTitle = $(this).val();
                if (userTitle != generateTitle()) {
                    Swal.fire({
                        title: "Bạn có muốn thay đổi title",
                        text: "Hành động của bạn có thể làm thay đổi title của hệ thống",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Vâng, thay đổi!",
                    }).then((result) => {
                        if (result.isConfirmed) {
                            Swal.fire(
                                "Đã thay đổi!",
                                "title đã được thay đổi.",
                                "success"
                            );
                            checkChangeTitle = true;
                        } else {
                            let result = generateTitle();
                            $("#title").val(result);
                        }
                    });
                }
            });

            $("#form-create-post").validate({
                rules: {
                    company: {
                        required: true,
                    }
                },
                // khi nào xử lí validate xong hết sẽ xử lí ở đây
                submitHandler: function() {
                    checkExitsCompany();
                }
            });
        });
    </script>
@endpush
