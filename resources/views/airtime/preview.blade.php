@extends('layouts.navbar')

@section('content')
    <!-- Table -->


    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    @if (session('status'))
        <script>
            swal("{{ session('status') }}");
        </script>
    @endif

    <div class="slider_area">
        <div class="single_slider d-flex align-items-center justify-content-center slider_bg_1 overlay2">
            <div class="container">
                <div class="row align-items-center justify-content-center">
                    <div class="col-xl-9">
                        <div class="slider_text text-center">
                            <table class="table table-responsive w-full ">
                                <thead>
                                    <tr>

                                        <th scope="col" class="text-light">name</th>
                                        <th scope="col" class="text-light">phone</th>
                                        <th scope="col" class="text-light">network</th>
                                        <th scope="col" class="text-light">amount</th>
                                        {{-- <th colspan="1">Action</th> --}}

                                    </tr>

                                </thead>
                                <tbody>
                                    @foreach ($datas as $data)
                                        <tr>
                                            <td class="text-light">{{ $data['name'] }}</td>
                                            <td class="text-light">{{ $data['phone'] }}</td>
                                            <td class="text-light">{{ $data['network'] }}</td>
                                            <td class="text-light">{{ $data['amount'] }}</td>

                                        </tr>
                                    @endforeach

                                </tbody>
                                
                                    <button type="submit" class="btn btn-primary"><a href="{{ url('airtime') }}">Go Back</a></button>
                                
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
