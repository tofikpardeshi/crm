@component('mail::message')
{{-- {{ $ProjectRegister['subject'] }} <br> --}}
{{ $ProjectRegister['body'] }}  <br>
{{-- {{ $ProjectRegister['cc'] }}  <br> --}}
{{ $ProjectRegister['line2'] }} <br>
{{ $ProjectRegister['line3'] }} <br>
{{ $ProjectRegister['line4'] }} <br> 
{{ $ProjectRegister['line5'] }} <br> 
{{ $ProjectRegister['line6'] }} <br>
{{ $ProjectRegister['line7'] }} <br>
{{ $ProjectRegister['line8'] }} <br>
{{ $ProjectRegister['line9'] }} <br>
{{ $ProjectRegister['line10'] }} <br>
{{ $ProjectRegister['line11'] }} <br>
{{ $ProjectRegister['line12'] ?? "" }} <br>
{{ $ProjectRegister['thanks'] }} <br> 
 
{{-- Thank You,<br>
{{ config('app.name') }} --}}
@endcomponent

 
