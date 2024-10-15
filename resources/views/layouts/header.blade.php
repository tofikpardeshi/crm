<?php $base_url = "http://ctportfolio.in/homents/"; ?>
<!DOCTYPE html>
<html lang="en">
    <head>

        <meta charset="utf-8" />
        <title>Homents</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="" name="description" />
        <meta content="" name="author" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <!-- App favicon -->
        <!--<link rel="shortcut icon" href="{{ url('') }}/assets/images/favicon.ico">-->
            
            @php
                $logo = DB::table('settings')->first();
                // dd($logo);
         @endphp
         @if($logo->site_logo == null)
            <link rel="shortcut icon" href="{{ url('') }}/assets/images/favicon.ico">
         @else
         <link rel="shortcut icon" href="{{ url(''.'/'.$logo->site_logo) }}">
         @endif
        
        
        <!-- plugin css -->
        <link href="{{ url('') }}/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
        <link href="{{ url('') }}/assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />
        <link href="{{ url('') }}/assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
        <link href="{{ url('') }}/assets/libs/datatables.net-select-bs4/css/select.bootstrap4.min.css" rel="stylesheet" type="text/css" />

        <link href="{{ url('') }}/assets/libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.css" rel="stylesheet" type="text/css" />
        <!-- Plugins css -->
        <link href="{{ url('') }}/assets/libs/mohithg-switchery/switchery.min.css" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.18/css/intlTelInput.css" />
        
        <link href="{{ url('') }}/assets/libs/multiselect/css/multi-select.css" rel="stylesheet" type="text/css" />
        <link href="{{ url('') }}/assets/libs/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
        <link href="{{ url('') }}/assets/libs/selectize/css/selectize.bootstrap3.css" rel="stylesheet" type="text/css" />
        <link href="{{ url('') }}/assets/libs/bootstrap-select/css/bootstrap-select.min.css" rel="stylesheet" type="text/css" />
        <link href="{{ url('') }}/assets/libs/bootstrap-touchspin/jquery.bootstrap-touchspin.min.css" rel="stylesheet" type="text/css" />
        <link href="{{ url('') }}/assets/libs/flatpickr/flatpickr.min.css" rel="stylesheet" type="text/css" />
        <link href="{{ url('') }}/assets/libs/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css" rel="stylesheet" type="text/css" />
        <link href="{{ url('') }}/assets/libs/clockpicker/bootstrap-clockpicker.min.css" rel="stylesheet" type="text/css" />
        <link href="{{ url('') }}/assets/libs/bootstrap-datepicker/css/bootstrap-datepicker.min.css" rel="stylesheet" type="text/css" />
        <link href="{{ url('') }}/assets/libs/dropzone/min/dropzone.min.css" rel="stylesheet" type="text/css" />
        <link href="{{ url('') }}/assets/libs/dropify/css/dropify.min.css" rel="stylesheet" type="text/css" />
        <link href="{{ url('') }}/assets/libs/cropper/cropper.min.css" rel="stylesheet" type="text/css" />

        <!-- App css -->
        <link href="{{ url('') }}/assets/css/bootstrap-purple.min.css" rel="stylesheet" type="text/css" id="bs-default-stylesheet" />
        <link href="{{ url('') }}/assets/css/app-purple.min.css" rel="stylesheet" type="text/css" id="app-default-stylesheet" />
        
        <link href="{{ url('') }}/assets/css/bootstrap-purple-dark.min.css" rel="stylesheet" type="text/css" id="bs-dark-stylesheet" disabled />
        <link href="{{ url('') }}/assets/css/app-purple-dark.min.css" rel="stylesheet" type="text/css" id="app-dark-stylesheet"  disabled />

        <!-- icons -->
        <link href="{{ url('') }}/assets/css/icons.min.css" rel="stylesheet" type="text/css" />
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        
        {{-- <link href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css" rel="stylesheet" />
        
        <link href="https://cdn.datatables.net/datetime/1.1.2/css/dataTables.dateTime.min.css" rel="stylesheet" /> --}}

        {{-- <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css" />--}}
<!-- DataTables Buttons CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.1.1/css/buttons.dataTables.min.css">


        

     


    </head>
