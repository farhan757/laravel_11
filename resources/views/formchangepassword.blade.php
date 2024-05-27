@extends('layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Change Password
        </h1>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">

                    </div>
                    <form class="form" autocomplete="off" id="form-changepassword">
                        @csrf

                        <div class="box-body">

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="oldpassword">Old Password</label>
                                        <input type="password" class="form-control" id="oldpassword" name="oldpassword">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="newpassword">New Password</label>
                                        <input type="password" class="form-control" id="newpassword" name="newpassword">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="newpassword_confirmation">Confirm New Password</label>
                                        <input type="password" class="form-control" id="newpassword_confirmation"
                                            name="newpassword_confirmation">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <a id="submit" class="btn btn-success btn-xs close-modal"><i class="fa fa-save mr-1"></i>
                                Save</a>
                        </div>
                    </form>
                </div>


                <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
                    <form id="form">
                        @csrf
                        <div>
                            <label class="block font-medium text-sm text-gray-700" for="task" value="task" />
                            <input type='task' name='message' placeholder='Task'
                                class="w-full rounded-md py-2.5 px-4 border text-sm outline-[#f84525]" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <button id="submit" type="button" onclick="submitForm()"
                                class="ms-4 inline-flex items-center px-4 py-2 bg-[#f84525] border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-800 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Add Task
                            </button>
                        </div>
                    </form>

                    <div id="card">

                    </div>
                </div>

            </div>
        </div>
    </section>
@endsection

@section('js')
    <script>
        $(document).ready(function() {

            $("#submit").click(function(e) {
                var formdata = new FormData($("#form-changepassword")[0]);

                $.ajax({
                    url: "{{ route('changepassword') }}",
                    type: "POST",
                    data: formdata,
                    processData: false,
                    contentType: false,
                    success: function(rs) {
                        console.log(rs);
                        msgBoxSweetBasic(rs.status, rs.message, rs.status,
                            "{{ route('form.changepass') }}");
                    },
                    error: function(rs) {
                        msgBoxSweetBasic(rs.status, rs.message, rs.status, null);
                    }
                });
            });
        });

        function submitForm() {
            const form = document.getElementById("form")
            var formData = new FormData(form);

            fetch("{{ route('sendMessage') }}", {
                    method: 'POST',
                    body: formData
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Terjadi kesalahan saat melakukan request.');
                    }
                    return response.text();
                })
                .then(data => {
                    console.log(data);
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }

        window.onload = function() {
            var channel = Echo.channel('channel-reverb');
            channel.listen("SendMessageEvent", function(data) {
                console.log(data);
                const card = document.getElementById("card")

                card.innerHTML += `<div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
                    <h1>${data.message}</h1>
                </div>`
            })
        }
    </script>
@endsection
