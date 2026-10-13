<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $action === 'accept' ? __('Accept Invitation') : __('Cancel Invitation') }}</title>
    <style>
        body { font-family: system-ui, sans-serif; background: #f3f4f6; display: flex; min-height: 100vh; align-items: center; justify-content: center; margin: 0; }
        .card { background: #fff; padding: 2rem; border-radius: .75rem; max-width: 26rem; box-shadow: 0 1px 3px rgba(0,0,0,.15); text-align: center; }
        button { padding: .6rem 1.4rem; border: 0; border-radius: .5rem; color: #fff; font-size: 1rem; cursor: pointer; background: {{ $action === 'accept' ? '#16a34a' : '#dc2626' }}; }
    </style>
</head>
<body>
<div class="card">
    <p>
        @if ($action === 'accept')
            {{ __('You have been invited to join the :team team!', ['team' => $invitation->team->name]) }}
        @else
            {{ __('Cancel the invitation for :email to the :team team?', ['email' => $invitation->email, 'team' => $invitation->team->name]) }}
        @endif
    </p>
    <form method="POST" action="{{ request()->fullUrl() }}">
        @csrf
        <button type="submit">{{ $action === 'accept' ? __('Accept Invitation') : __('Cancel Invitation') }}</button>
    </form>
</div>
</body>
</html>
