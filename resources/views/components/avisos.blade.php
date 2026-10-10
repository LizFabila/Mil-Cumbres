@if (session('ok'))
    <div class="aviso aviso--ok" role="status">{{ session('ok') }}</div>
@endif
@if ($errors->any())
    <div class="aviso aviso--error" role="alert">{{ $errors->first() }}</div>
@endif
