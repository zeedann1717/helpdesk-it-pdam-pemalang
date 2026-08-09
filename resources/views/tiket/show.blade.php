@extends('layouts.app')

@section('title', 'Detail Tiket')
@section('page-title', 'Detail Tiket / '.$tiket->kode_tiket)

@push('styles')
<style>
    .chat-box { display: flex; flex-direction: column; height: 460px; }
    .chat-messages {
        flex: 1; overflow-y: auto; padding: 14px;
        background: #f7f9fc; border-radius: 10px;
        display: flex; flex-direction: column; gap: 10px;
    }
    .bubble {
        max-width: 78%; padding: 8px 12px; border-radius: 14px;
        font-size: .87rem; line-height: 1.35; position: relative;
    }
    .bubble .meta { display: block; font-size: .68rem; opacity: .7; margin-top: 3px; }
    .bubble.me {
        align-self: flex-end; background: #0d3b8c; color: #fff;
        border-bottom-right-radius: 4px;
    }
    .bubble.them {
        align-self: flex-start; background: #fff; color: #111827;
        border: 1px solid #e5e7eb; border-bottom-left-radius: 4px;
    }
    .bubble .sender { font-weight: 600; font-size: .74rem; display: block; margin-bottom: 2px; }
    .chat-empty { text-align: center; color: #9ca3af; font-size: .85rem; margin-top: 30px; }
    .chat-input-row { display: flex; gap: 8px; margin-top: 12px; }
</style>
@endpush

@section('content')
<div class="row g-4">
    <div class="col-12 col-lg-6">
        <div class="card stat-card">
            <div class="card-header bg-white fw-semibold">Detail Tiket ({{ $tiket->kode_tiket }})</div>
            <div class="card-body">
                <table class="table table-borderless mb-0">
                    <tr>
                        <td class="text-muted" style="width:180px">Kode Tiket</td>
                        <td class="fw-semibold">: {{ $tiket->kode_tiket }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Nama Lengkap</td>
                        <td>: {{ $tiket->user->name }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Devisi</td>
                        <td>: {{ $tiket->divisi?->nama_divisi ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Unit</td>
                        <td>: {{ $tiket->unit ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Lokasi</td>
                        <td>: {{ $tiket->lokasi?->nama_lokasi ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted align-top">Keluhan/Masalah</td>
                        <td>: {{ $tiket->keluhan }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted align-top">Foto</td>
                        <td>
                            :
                            @if ($tiket->foto)
                                <br>
                                <a href="{{ asset('storage/'.$tiket->foto) }}" target="_blank">
                                    <img src="{{ asset('storage/'.$tiket->foto) }}" alt="Foto kendala" class="img-fluid rounded mt-2" style="max-width:320px;">
                                </a>
                            @else
                                Tidak ada foto
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">Tanggal Dibuat</td>
                        <td>: {{ $tiket->created_at->format('d-m-Y H:i') }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Tanggal Selesai</td>
                        <td>: {{ $tiket->tanggal_selesai?->format('d-m-Y H:i') ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Status</td>
                        <td>: <span class="badge {{ $tiket->statusBadgeClass() }}">{{ $tiket->statusLabel() }}</span></td>
                    </tr>
                    @if ($tiket->catatan_admin)
                        <tr>
                            <td class="text-muted align-top">Catatan Admin</td>
                            <td>: {{ $tiket->catatan_admin }}</td>
                        </tr>
                    @endif
                </table>
            </div>
        </div>

        @if (auth()->user()->isAdmin())
            <div class="card stat-card mt-4">
                <div class="card-header bg-white fw-semibold">Update Status Tiket</div>
                <div class="card-body">
                    <form action="{{ route('tiket.updateStatus', $tiket) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select" required>
                                <option value="waiting" @selected($tiket->status == 'waiting')>Waiting</option>
                                <option value="in_progress" @selected($tiket->status == 'in_progress')>In Progress</option>
                                <option value="done" @selected($tiket->status == 'done')>Done</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Catatan Admin (opsional)</label>
                            <textarea name="catatan_admin" rows="3" class="form-control">{{ $tiket->catatan_admin }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fa-solid fa-floppy-disk me-1"></i> Simpan
                        </button>
                    </form>
                </div>
            </div>
        @endif
    </div>

    {{-- ==== Live Chat per tiket ==== --}}
    <div class="col-12 col-lg-6">
        <div class="card stat-card">
            <div class="card-header bg-white fw-semibold d-flex align-items-center justify-content-between">
                <span><i class="fa-solid fa-comments me-2 text-primary"></i>Percakapan Tiket</span>
                <span class="badge bg-success-subtle text-success" id="chatStatus">
                    <i class="fa-solid fa-circle fa-xs"></i> Live
                </span>
            </div>
            <div class="card-body chat-box">
                <div class="chat-messages" id="chatMessages">
                    <div class="chat-empty" id="chatEmpty">Memuat percakapan...</div>
                </div>
                <form id="chatForm" class="chat-input-row">
                    <input type="text" id="chatInput" class="form-control" placeholder="Tulis pesan..." maxlength="2000" autocomplete="off" required>
                    <button type="submit" class="btn btn-primary px-3">
                        <i class="fa-solid fa-paper-plane"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<a href="{{ url()->previous() }}" class="btn btn-outline-secondary mt-3">
    <i class="fa-solid fa-arrow-left me-1"></i> Kembali
</a>
@endsection

@push('scripts')
<script>
(function () {
    const tiketId = {{ $tiket->id }};
    const currentUserId = {{ auth()->id() }};
    const messagesEl = document.getElementById('chatMessages');
    const emptyEl = document.getElementById('chatEmpty');
    const form = document.getElementById('chatForm');
    const input = document.getElementById('chatInput');
    
    // Pastikan layout memiliki meta csrf-token
    const csrfMeta = document.querySelector('meta[name="csrf-token"]');
    if (!csrfMeta) {
        console.error("Meta tag CSRF Token tidak ditemukan di layouts/app.blade.php!");
        return;
    }
    const csrfToken = csrfMeta.content;

    // URL Route yang aman dan dinamis
    const urlChatIndex = '{{ route("tiket.chat.index", $tiket->id) }}';
    const urlChatStore = '{{ route("tiket.chat.store", $tiket->id) }}';

    function renderBubble(m) {
        const wrap = document.createElement('div');
        wrap.className = 'bubble ' + (m.is_me ? 'me' : 'them');
        wrap.innerHTML = `
            ${m.is_me ? '' : `<span class="sender">${m.sender_name}${m.sender_is_admin ? ' (Admin)' : ''}</span>`}
            ${escapeHtml(m.message)}
            <span class="meta">${m.created_at}</span>
        `;
        messagesEl.appendChild(wrap);
        messagesEl.scrollTop = messagesEl.scrollHeight;
    }

    function escapeHtml(str) {
        const div = document.createElement('div');
        div.textContent = str;
        return div.innerHTML;
    }

    // Ambil riwayat chat pertama kali
    fetch(urlChatIndex, { headers: { 'Accept': 'application/json' } })
        .then(res => {
            if (!res.ok) throw new Error('Gagal memuat chat');
            return res.json();
        })
        .then(data => {
            emptyEl.remove();
            if (data.length === 0) {
                messagesEl.innerHTML = '<div class="chat-empty">Belum ada percakapan. Mulai kirim pesan di bawah.</div>';
                return;
            }
            data.forEach(renderBubble);
        })
        .catch((err) => {
            console.error(err);
            emptyEl.textContent = 'Gagal memuat percakapan (Cek console log).';
        });

    // Kirim pesan baru
    form.addEventListener('submit', function (e) {
        e.preventDefault();
        const message = input.value.trim();
        if (!message) return;

        input.disabled = true;

        fetch(urlChatStore, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
            body: JSON.stringify({ message }),
        })
            .then(res => {
                if (!res.ok) throw new Error('Pesan gagal dikirim');
                return res.json();
            })
            .then(res => {
                document.querySelector('.chat-empty')?.remove();
                renderBubble(res.data);
                input.value = '';
            })
            .catch(err => {
                alert('Gagal mengirim pesan: ' + err.message);
            })
            .finally(() => { 
                input.disabled = false; 
                input.focus(); 
            });
    });

    // Fitur Broadcast/Websocket (Opsional)
    if (window.Echo) {
        window.Echo.private(`tiket.${tiketId}`).listen('.new-message', (e) => {
            if (e.sender_id === currentUserId) return;
            document.querySelector('.chat-empty')?.remove();
            renderBubble({
                message: e.message,
                sender_name: e.sender_name,
                sender_is_admin: e.sender_is_admin,
                is_me: false,
                created_at: e.created_at,
            });
        });
    }
})();
</script>
@endpush