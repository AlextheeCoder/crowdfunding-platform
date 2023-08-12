@if(session()->has('message'))
<div x-data="{show: true}" x-init="setTimeout(() => show = false, 3000)" x-show="show" class="message">
  <p>
    {{session('message')}}
  </p>
</div>
@endif