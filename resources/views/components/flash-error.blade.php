@if(session()->has('error'))
<div x-data="{show: true}" x-init="setTimeout(() => show = false, 3000)" x-show="show" class="error">
  <p>
    {{session('error')}}
  </p>
</div>
@endif