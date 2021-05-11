<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'DHP Internal')
<img src="{{asset('image/logo.png')}}" class="logo" alt="{{config('app.name')}}">
@else
{{ $slot }}
@endif
</a>
</td>
</tr>
