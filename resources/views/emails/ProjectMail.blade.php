@component('mail::message')
{{ $ProjectDetails['title'] }}<br>
{{ $ProjectDetails['name'] ?? "" }} <br>
{{ $ProjectDetails['body'] }}<br>

{{-- @component('mail::button', ['url' => ''])
Button Text
@endcomponent --}}

Thank You,<br>
{{ config('app.name') }}
@endcomponent

