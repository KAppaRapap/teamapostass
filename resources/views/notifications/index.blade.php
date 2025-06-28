@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">
        <h2 class="text-2xl font-bold text-white mb-0">Notificações</h2>
        @if($notifications->count() > 0)
        <div class="flex gap-2">
            <form action="{{ route('notifications.mark-all-as-read') }}" method="POST">
                @csrf
                <button type="submit" class="flex items-center gap-2 px-4 py-2 rounded bg-gray-800 text-green-400 border border-green-500 hover:bg-green-500 hover:text-white transition">
                    <i class="fas fa-check-double"></i> Marcar Todas como Lidas
                </button>
            </form>
            <form action="{{ route('notifications.destroy-all') }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="flex items-center gap-2 px-4 py-2 rounded bg-gray-800 text-red-400 border border-red-500 hover:bg-red-500 hover:text-white transition" onclick="return confirm('Tem certeza que deseja excluir TODAS as notificações? Esta ação não pode ser desfeita.')">
                    <i class="fas fa-trash-alt"></i> Excluir Todas
                </button>
            </form>
        </div>
        @endif
    </div>

    @if(session('success'))
    <div class="bg-green-600 text-white rounded p-3 mb-4">{{ session('success') }}</div>
    @endif
    @if(session('error'))
    <div class="bg-red-600 text-white rounded p-3 mb-4">{{ session('error') }}</div>
    @endif

    <div class="bg-gray-900 rounded-lg shadow p-6 border border-gray-800">
        @if($notifications->count() > 0)
        <div class="flex flex-col gap-4">
            @foreach($notifications as $notification)
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 p-4 rounded-lg border border-gray-800 bg-gray-800 shadow-sm {{ $notification->is_read ? '' : 'ring-2 ring-green-500' }}">
                <div class="flex items-center gap-4">
                    <div class="flex items-center justify-center w-12 h-12 rounded-full {{
                        match($notification->type) {
                            'group_join' => 'bg-green-600',
                            'group_leave' => 'bg-yellow-600',
                            'new_draw' => 'bg-blue-600',
                            'draw_result' => 'bg-purple-600',
                            'balance_adjustment' => isset($notification->data['color']) ? str_replace('bg-', 'bg-', $notification->data['color']) : 'bg-blue-600',
                            default => 'bg-gray-700',
                        }
                    }} text-white text-xl">
                        @switch($notification->type)
                            @case('group_join')
                                <i class="fas fa-users"></i>
                                @break
                            @case('group_leave')
                                <i class="fas fa-sign-out-alt"></i>
                                @break
                            @case('new_draw')
                                <i class="fas fa-calendar-alt"></i>
                                @break
                            @case('draw_result')
                                <i class="fas fa-trophy"></i>
                                @break
                            @case('balance_adjustment')
                                <i class="{{ isset($notification->data['icon']) ? $notification->data['icon'] : 'fas fa-wallet' }}"></i>
                                @break
                            @default
                                <i class="fas fa-bell"></i>
                        @endswitch
                    </div>
                    <div>
                        <h6 class="text-lg font-semibold text-white mb-1">{{ $notification->title }}</h6>
                        <p class="text-gray-300 mb-1">{{ $notification->message }}</p>
                        <small class="text-gray-400">{{ $notification->created_at->diffForHumans() }}</small>
                    </div>
                </div>
                <div class="flex gap-2">
                    @if(!$notification->is_read)
                    <form action="{{ route('notifications.mark-as-read', $notification) }}" method="POST">
                        @csrf
                        <button type="submit" class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-700 text-green-400 border border-green-500 hover:bg-green-500 hover:text-white transition" title="Marcar como lida">
                            <i class="fas fa-check"></i>
                        </button>
                    </form>
                    @endif
                    <form action="{{ route('notifications.destroy', $notification) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-700 text-red-400 border border-red-500 hover:bg-red-500 hover:text-white transition" title="Excluir" onclick="return confirm('Tem certeza que deseja excluir esta notificação?')">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
        <div class="mt-6">
            {{ $notifications->links('groups.pagination') }}
        </div>
        @else
        <div class="flex flex-col items-center justify-center py-12">
            <i class="fas fa-bell-slash fa-4x text-gray-600 mb-4"></i>
            <h4 class="text-white text-xl font-semibold mb-2">Nenhuma notificação</h4>
            <p class="text-gray-400">Você não tem notificações no momento.</p>
        </div>
        @endif
    </div>
</div>
@endsection
