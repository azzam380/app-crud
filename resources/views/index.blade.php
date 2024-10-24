<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Member Management | Live CRUD Tutorial @ qadrlabs.com</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
        td {
            cursor: pointer;
        }

        .editor {
            display: none;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h2>Member Management</h2>
            <div class="card">
                <div class="card-body">
                    <button class="btn btn-primary mb-3 float-end" id="tambah-data"><i class="fa-solid fa-plus"></i> Tambah</button>

                    <table id="table-data" class="table table-striped table-bordered mt-3">
                        <thead>
                        <tr>
                            <th width="30%">name</th>
                            <th width="30%">Email</th>
                            <th width="20%">Phone</th>
                            <th width="20%">Hapus</th>
                        </tr>
                        </thead>
                        <tbody id="table-body">
                        @foreach ($members as $member)
                            <tr data-id="{{ $member->id }}">
                                <td><span class='span-name caption' data-id='{{ $member->id }}'>{{ $member->name }}</span>
                                    <input type='text' class='field-name form-control editor' value='{{ $member->name }}'
                                           data-id='{{ $member->id }}'/></td>
                                <td><span class='span-email caption' data-id='{{ $member->id }}'>{{ $member->email }}</span>
                                    <input type='text' class='field-email form-control editor' value='{{ $member->email }}'
                                           data-id='{{ $member->id }}'/></td>
                                <td><span class='span-phone caption' data-id='{{ $member->id }}'>{{ $member->phone }}</span>
                                    <input type='text' class='field-phone form-control editor' value='{{ $member->phone }}'
                                           data-id='{{ $member->id }}'/></td>
                                <td>
                                    <button class='btn btn-xs btn-danger hapus-member' data-id='{{ $member->id }}'>
                                        <i class="fa-solid fa-trash"></i> Hapus
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script>
    $(function () {
        $.ajaxSetup({
            type: "post",
            cache: false,
            dataType: "json",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).on("click", "td", function () {
            $(this).find("span[class~='caption']").hide();
            $(this).find("input[class~='editor']").fadeIn().focus();
        });

        $("#tambah-data").click(function () {
            $.ajax({
                url: "{{ route('members.create') }}",
                success: function (a) {
                    var ele = "";
                    ele += "<tr data-id='" + a.id + "'>";
                    ele += "<td><span class='span-name caption' data-id='" + a.id + "'></span> <input type='text' class='field-name form-control editor' data-id='" + a.id + "' /></td>";
                    ele += "<td><span class='span-email caption' data-id='" + a.id + "'></span> <input type='text' class='field-email form-control editor' data-id='" + a.id + "' /></td>";
                    ele += "<td><span class='span-phone caption' data-id='" + a.id + "'></span> <input type='text' class='field-phone form-control editor' data-id='" + a.id + "' /></td>";
                    ele += "<td><button class='btn btn-xs btn-danger hapus-member' data-id='" + a.id + "'><i class='fa-solid fa-trash'></i> Hapus</button></td>";
                    ele += "</tr>";

                    var element = $(ele);
                    element.hide();
                    element.prependTo("#table-body").fadeIn(1500);
                }
            });
        });

        $(document).on("keydown", ".editor", function (e) {
            if (e.keyCode == 13) {
                var target = $(e.target);
                var value = target.val();
                var id = target.attr("data-id");
                var data = {id: id, value: value};
                if (target.is(".field-name")) {
                    data.modul = "name";
                } else if (target.is(".field-email")) {
                    data.modul = "email";
                } else if (target.is(".field-phone")) {
                    data.modul = "phone";
                }

                $.ajax({
                    data: data,
                    url: "{{ route('members.update') }}",
                    success: function () {
                        target.hide();
                        target.siblings("span[class~='caption']").html(value).fadeIn();
                    }
                })
            }
        });

        $(document).on("click", ".hapus-member", function () {
            var id = $(this).attr("data-id");

            swal({
                title: "Hapus Member",
                text: "Yakin akan menghapus member ini?",
                icon: "warning",
                buttons: {
                    cancel: "Batal",
                    confirm: {
                        text: "Hapus",
                        value: true,
                        visible: true,
                        className: "btn-danger",
                        closeModal: false
                    }
                },
                dangerMode: true,
            })
                .then((willDelete) => {
                    if (willDelete) {
                        $.ajax({
                            url: "{{ route('members.delete') }}",
                            data: {id: id},
                            success: function () {
                                $("tr[data-id='" + id + "']").fadeOut("fast", function () {
                                    $(this).remove();
                                });

                                swal("Member berhasil dihapus!", {
                                    icon: "success",
                                });
                            },
                            error: function() {
                                swal("Terjadi kesalahan dalam server!", "error");
                            }
                        });
                    } else {
                        swal("Hapus Member dibatalkan");
                    }
                });
        });
    });
</script>
</body>
</html>
