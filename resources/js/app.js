import './bootstrap'; // WAJIB

import Alpine from 'alpinejs';
window.Alpine = Alpine;
Alpine.start();

// Ambil ID pelanggan dari meta tag
const pelangganId = document.head.querySelector('meta[name="pelanggan-id"]')?.content;

if (pelangganId) {
    console.log('📢 Listening to pelanggan.' + pelangganId);

    Echo.channel(`pelanggan.${pelangganId}`)
        .listen('NotifikasiPesananUpdated', (event) => {
            console.log("📡 Notifikasi masuk:", event);

            // Fetch ulang data notifikasi dari server
            fetch('/notifikasi-pelanggan/json')
                .then(res => res.json())
                .then(updateNotifikasiList);
        });

    // Fetch awal ketika halaman load
    fetch('/notifikasi-pelanggan/json')
        .then(res => res.json())
        .then(updateNotifikasiList);

    // Handler tombol baca & hapus
    document.addEventListener('click', function (e) {
        if (e.target.matches('[data-read-id]')) {
            const id = e.target.dataset.readId;
            fetch(`/notifikasi/${id}/read`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                }
            }).then(() => {
                fetch('/notifikasi-pelanggan/json')
                    .then(res => res.json())
                    .then(updateNotifikasiList);
            });
        }

        if (e.target.matches('[data-delete-id]')) {
            const id = e.target.dataset.deleteId;
            fetch(`/notifikasi/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                }
            }).then(() => {
                fetch('/notifikasi-pelanggan/json')
                    .then(res => res.json())
                    .then(updateNotifikasiList);
            });
        }
    });
}

function updateNotifikasiList(data) {
    const list = document.getElementById('notif-list');
    if (!list) return;
    list.innerHTML = '';

    data.forEach(notif => {
        const item = document.createElement('div');
        item.className = `relative px-6 py-4 transition-all duration-200 ${notif.is_read ? '' : 'bg-green-100 border-l-4 border-green-500'} hover:bg-gray-50`;

        item.innerHTML = `
            <div class="text-sm font-bold text-gray-800 mb-1">
                <i class="fas fa-receipt mr-2 text-green-500"></i>
                Kode: ${notif.kode_booking}
            </div>

            <div class="text-sm text-gray-700 font-bold mb-1">
                Status pemesanan: ${notif.status ?? '-'}
            </div>
            
            
            <div class="text-sm text-gray-600 mb-1 italic">
                ${getKategoriLabel(notif.kategori)}
            </div>

            <div class="text-sm text-gray-700 mb-1">
                ${notif.pesan.replace(/\n/g, '<br>')}
            </div>


            <div class="text-sm text-gray-500 mb-2">
                ${new Date(notif.created_at).toLocaleString('id-ID')}
            </div>


            <div class="flex gap-2 text-xs text-gray-600">
                ${!notif.is_read ? `<button data-read-id="${notif.id}" class="text-green-600 hover:underline">Tandai dibaca</button>` : ''}
                <button data-delete-id="${notif.id}" class="text-red-600 hover:underline">Hapus</button>
            </div>
        `;

        list.appendChild(item);
    });

    const unreadCount = data.filter(n => !n.is_read).length;
    updateBadge(unreadCount);
}

function updateBadge(count) {
    const badge = document.getElementById('notif-count');
    const bellBtn = document.querySelector('.fa-bell')?.parentElement;

    if (count > 0) {
        if (badge) {
            badge.innerText = count;
        } else if (bellBtn) {
            const badgeEl = document.createElement('span');
            badgeEl.id = 'notif-count';
            badgeEl.className = 'absolute -top-1 -right-1 bg-red-600 text-white text-xs font-bold px-1.5 py-0.5 rounded-full shadow';
            badgeEl.innerText = count;
            bellBtn.appendChild(badgeEl);
        }
    } else if (badge) {
        badge.remove();
    }
}

function getKategoriLabel(kategori) {
    switch (kategori) {
        case 'admin':
            return '📋 Pesan dari Admin';
        case 'mekanik':
            return '🛠️ Pesan dari Mekanik';
        case 'status':
            return '🔄 Update Status';
        default:
            return '📢 Informasi';
    }
}
