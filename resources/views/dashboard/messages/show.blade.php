@extends('layouts.dashboard')

@section('title', 'Chat dengan ' . $otherUser->name)
@section('user_name', Auth::user()->name)
@section('user_role', Auth::user()->role == 'employer' ? 'Recruiter' : 'Kandidat Profesional')

@section('sidebar_menu')
    @if(Auth::user()->role == 'employer')
        <li><a href="{{ route('employer.dashboard') }}" class="sidebar-link"><i class="fa-solid fa-house w-5 text-center"></i> Beranda</a></li>
        <li><a href="{{ route('employer.jobs.create') }}" class="sidebar-link"><i class="fa-solid fa-bullhorn w-5 text-center"></i> Pasang Lowongan</a></li>
        <li><a href="{{ route('employer.jobs.index') }}" class="sidebar-link"><i class="fa-solid fa-list-check w-5 text-center"></i> Lowongan Aktif</a></li>
        <li><a href="{{ route('employer.candidates.index') }}" class="sidebar-link"><i class="fa-solid fa-users-viewfinder w-5 text-center"></i> Kandidat Masuk</a></li>
        <li>
        <a href="{{ route('employer.messages.index') }}" class="sidebar-link active">
            <i class="fa-solid fa-comment-dots w-5 text-center"></i> 
            <span class="flex-1">Pesan</span>
            @php $unreadCount = \App\Models\Message::where('receiver_id', Auth::id())->where('is_read', false)->count(); @endphp
            @if($unreadCount > 0)
                <span class="bg-rose-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full">{{ $unreadCount }}</span>
            @endif
        </a>
    </li>
        <li><a href="{{ route('employer.profile.index') }}" class="sidebar-link"><i class="fa-solid fa-building w-5 text-center"></i> Profil Perusahaan</a></li>
        <li><a href="{{ route('employer.settings.index') }}" class="sidebar-link"><i class="fa-solid fa-gear w-5 text-center"></i> Pengaturan</a></li>
    @else
        <li><a href="{{ route('jobseeker.dashboard') }}" class="sidebar-link"><i class="fa-solid fa-house w-5 text-center"></i> Beranda</a></li>
        <li><a href="{{ route('jobseeker.jobs.index') }}" class="sidebar-link"><i class="fa-solid fa-magnifying-glass w-5 text-center"></i> Cari Lowongan</a></li>
        <li><a href="{{ route('jobseeker.applications.index') }}" class="sidebar-link"><i class="fa-solid fa-paper-plane w-5 text-center"></i> Lamaran Saya</a></li>
        <li><a href="{{ route('jobseeker.saved.index') }}" class="sidebar-link"><i class="fa-solid fa-bookmark w-5 text-center"></i> Tersimpan</a></li>
        <li>
        <a href="{{ route('jobseeker.messages.index') }}" class="sidebar-link active">
            <i class="fa-solid fa-comment-dots w-5 text-center"></i> 
            <span class="flex-1">Pesan</span>
            @php $unreadCount = \App\Models\Message::where('receiver_id', Auth::id())->where('is_read', false)->count(); @endphp
            @if($unreadCount > 0)
                <span class="bg-rose-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full">{{ $unreadCount }}</span>
            @endif
        </a>
    </li>
        <li><a href="{{ route('jobseeker.profile.index') }}" class="sidebar-link"><i class="fa-solid fa-user w-5 text-center"></i> Profil Saya</a></li>
        <li><a href="{{ route('jobseeker.settings.index') }}" class="sidebar-link"><i class="fa-solid fa-gear w-5 text-center"></i> Pengaturan</a></li>
    @endif
@endsection

@section('content')
@php
    $routePrefix = Auth::user()->role == 'employer' ? 'employer.' : 'jobseeker.';
@endphp
<div class="glass-panel rounded-2xl overflow-hidden flex flex-col h-[calc(100vh-140px)]">
    
    <div class="p-4 border-b border-slate-100 bg-white/80 flex items-center gap-4 shrink-0 shadow-sm z-10">
        <a href="{{ route($routePrefix . 'messages.index') }}" class="w-10 h-10 rounded-full flex items-center justify-center text-slate-500 hover:bg-slate-100 hover:text-slate-800 transition-colors">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <div class="w-10 h-10 rounded-full bg-slate-200 overflow-hidden shrink-0">
            <img src="https://ui-avatars.com/api/?name={{ urlencode($otherUser->name) }}&background=f1f5f9" class="w-full h-full object-cover">
        </div>
        <div>
            <h3 class="font-bold text-slate-800">{{ $otherUser->name }}</h3>
            <div class="text-xs font-semibold text-slate-500">{{ $otherUser->role == 'jobseeker' ? 'Pelamar' : 'Perusahaan' }}</div>
        </div>
    </div>
    
    
    <div id="chat-container" class="flex-1 overflow-y-auto p-6 space-y-4 bg-slate-50/50">
        @php $lastDate = null; @endphp
        @foreach($messages as $msg)
            @php
                $msgDate = $msg->created_at->format('Y-m-d');
                $humanDate = $msg->created_at->isToday() ? 'Hari Ini' : ($msg->created_at->isYesterday() ? 'Kemarin' : $msg->created_at->format('d M Y'));
            @endphp
            @if($msgDate !== $lastDate)
                <div class="flex justify-center my-4 date-separator" data-date="{{ $msgDate }}">
                    <span class="text-[10px] font-bold bg-slate-200 text-slate-500 px-3 py-1 rounded-full uppercase tracking-wider">{{ $humanDate }}</span>
                </div>
                @php $lastDate = $msgDate; @endphp
            @endif

            @if($msg->sender_id == Auth::id())
                
                <div class="flex justify-end mb-4 chat-msg" data-date="{{ $msgDate }}">
                    <div class="max-w-[75%] bg-blue-600 text-white p-4 rounded-2xl rounded-tr-sm shadow-sm relative group">
                        <p class="text-sm font-medium">{{ $msg->content }}</p>
                        <div class="text-[10px] text-blue-200 mt-1 text-right">{{ $msg->created_at->format('H:i') }}</div>
                    </div>
                </div>
            @else
                
                <div class="flex justify-start mb-4 chat-msg" data-date="{{ $msgDate }}">
                    <div class="max-w-[75%] bg-white border border-slate-200 text-slate-700 p-4 rounded-2xl rounded-tl-sm shadow-sm relative group">
                        <p class="text-sm font-medium">{{ $msg->content }}</p>
                        <div class="text-[10px] text-slate-400 mt-1">{{ $msg->created_at->format('H:i') }}</div>
                    </div>
                </div>
            @endif
        @endforeach
    </div>
    
    
    <div class="p-4 bg-white border-t border-slate-100 shrink-0">
        <form id="chat-form" action="{{ route($routePrefix . 'messages.store', $otherUser->id) }}" method="POST" class="flex items-end gap-3">
            @csrf
            <div class="flex-1 relative">
                <textarea id="chat-input" name="content" rows="1" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all resize-none text-sm font-medium" placeholder="Ketik pesan Anda..." required></textarea>
            </div>
            <button type="submit" class="w-12 h-12 rounded-xl bg-blue-600 text-white flex items-center justify-center hover:bg-blue-700 transition-colors shadow-sm shrink-0 disabled:opacity-50" id="btn-send">
                <i class="fa-solid fa-paper-plane"></i>
            </button>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const chatContainer = document.getElementById('chat-container');
    const chatForm = document.getElementById('chat-form');
    const chatInput = document.getElementById('chat-input');
    const btnSend = document.getElementById('btn-send');
    

    const scrollToBottom = () => {
        chatContainer.scrollTop = chatContainer.scrollHeight;
    };
    scrollToBottom();
    

    chatInput.addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = (this.scrollHeight < 120 ? this.scrollHeight : 120) + 'px';
    });
    

    chatInput.addEventListener('keydown', function(e) {
        if(e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            if(this.value.trim() !== '') {
                chatForm.dispatchEvent(new Event('submit'));
            }
        }
    });


    chatForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const content = chatInput.value.trim();
        if(!content) return;
        
        btnSend.disabled = true;
        
        fetch(this.action, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ content: content })
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {

                appendMessage(data.message.content, data.message.time, true, data.message.date, data.message.date_human);
                chatInput.value = '';
                chatInput.style.height = 'auto';
            } else if (data.error) {
                alert(data.error);
            }
        })
        .catch(err => console.error(err))
        .finally(() => {
            btnSend.disabled = false;
            chatInput.focus();
        });
    });

    function appendMessage(content, time, isMine, dateStr, humanDateStr) {

        const existingSeparators = document.querySelectorAll('.date-separator');
        const lastSeparator = existingSeparators.length > 0 ? existingSeparators[existingSeparators.length - 1].getAttribute('data-date') : null;
        
        if (dateStr && dateStr !== lastSeparator) {
            const sep = document.createElement('div');
            sep.className = 'flex justify-center my-4 date-separator';
            sep.setAttribute('data-date', dateStr);
            sep.innerHTML = `<span class="text-[10px] font-bold bg-slate-200 text-slate-500 px-3 py-1 rounded-full uppercase tracking-wider">${humanDateStr}</span>`;
            chatContainer.appendChild(sep);
        }

        const div = document.createElement('div');
        div.className = isMine ? 'flex justify-end mb-4 chat-msg' : 'flex justify-start mb-4 chat-msg';
        div.setAttribute('data-date', dateStr || lastSeparator);
        
        const bubbleClass = isMine 
            ? 'max-w-[75%] bg-blue-600 text-white p-4 rounded-2xl rounded-tr-sm shadow-sm relative group'
            : 'max-w-[75%] bg-white border border-slate-200 text-slate-700 p-4 rounded-2xl rounded-tl-sm shadow-sm relative group';
            
        const timeClass = isMine ? 'text-[10px] text-blue-200 mt-1 text-right' : 'text-[10px] text-slate-400 mt-1';
        
        div.innerHTML = `
            <div class="${bubbleClass}">
                <p class="text-sm font-medium">${escapeHTML(content)}</p>
                <div class="${timeClass}">${time}</div>
            </div>
        `;
        chatContainer.appendChild(div);
        scrollToBottom();
    }
    
    function escapeHTML(str) {
        return str.replace(/[&<>'"]/g, 
            tag => ({
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                "'": '&#39;',
                '"': '&quot;'
            }[tag] || tag)
        );
    }


    setInterval(() => {
        fetch(window.location.href, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => res.json())
        .then(data => {


            const currentCount = document.querySelectorAll('.chat-msg').length;
            if (data.length > currentCount) {
                chatContainer.innerHTML = '';
                data.forEach(msg => {
                    appendMessage(msg.content, msg.time, msg.is_mine, msg.date, msg.date_human);
                });
            }
        })
        .catch(err => console.error(err));
    }, 5000);
</script>
@endsection
