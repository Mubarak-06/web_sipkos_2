<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIPKOS - Sistem Informasi Kos</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">

    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
</head>

<body class="bg-slate-50 font-sans antialiased min-h-screen flex flex-col">

    <nav class="bg-white border-b border-slate-100 sticky top-0 z-50 shadow-sm w-full">
        <div class="max-w-7xl mx-auto px-6 h-16 flex items-center justify-between">
            <a href="{{ route('home') }}" class="text-2xl font-black tracking-wider text-blue-600">
                SIPKOS
            </a>

            <div class="flex items-center space-x-8 font-semibold text-sm text-slate-600">
                <a href="{{ route('home') }}"
                    class="{{ Request::is('/') ? 'text-blue-600 border-b-2 border-blue-600 pb-5 pt-5 mt-1' : 'hover:text-blue-500' }}">
                    Home
                </a>

                <a href="{{ route('my.bookings') }}"
                    class="{{ Request::is('my-bookings') ? 'text-blue-600 border-b-2 border-blue-600 pb-5 pt-5 mt-1' : 'hover:text-blue-500' }}">
                    My Bookings
                </a>

                <a href="{{ route('admin') }}"
                    class="{{ Request::is('admin*') ? 'text-blue-600 border-b-2 border-blue-600 pb-5 pt-5 mt-1' : 'hover:text-blue-500' }}">
                    Admin
                </a>
            </div>
        </div>
    </nav>

    <main class="w-full max-w-7xl mx-auto px-6 py-8 flex-1">
        @yield('content')
    </main>

    <!-- JQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- DataTables -->
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>

    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <div id="chatbotBox" class="fixed bottom-6 right-6 z-[9999]">
        <button id="btnChatbot"
            class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-full shadow-xl text-sm font-black">
            💬 Tanya SIPKOS AI
        </button>

        <div id="chatbotPanel"
            class="hidden mt-3 w-80 bg-white rounded-2xl shadow-2xl border border-slate-200 overflow-hidden">

            <div class="bg-blue-600 text-white p-4">
                <h3 class="font-black text-sm">SIPKOS AI</h3>
                <p class="text-xs text-blue-100 mt-1">
                    Halo, saya SIPKOS AI. Saya bisa bantu carikan kos sesuai budget, lokasi, tipe kos, dan fasilitas.
                </p>
            </div>

            <div id="chatMessages" class="h-80 overflow-y-auto p-4 space-y-3 bg-slate-50 text-sm">
                <div class="bg-white p-3 rounded-xl border text-slate-600 text-xs leading-relaxed">
                    Halo, saya SIPKOS AI. Mau cari kos seperti apa?
                </div>
            </div>

            <form id="chatbotForm" class="p-3 border-t bg-white flex gap-2">
                @csrf

                <input type="text" id="chatInput" placeholder="Cari kos pria di bawah 1 juta..."
                    class="flex-1 px-3 py-2 rounded-xl bg-slate-100 text-xs outline-none">

                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-xl text-xs font-bold">
                    Kirim
                </button>
            </form>
        </div>
    </div>

    <script>
        const btnChatbot = document.getElementById('btnChatbot');
        const chatbotPanel = document.getElementById('chatbotPanel');
        const chatbotForm = document.getElementById('chatbotForm');
        const chatInput = document.getElementById('chatInput');
        const chatMessages = document.getElementById('chatMessages');

        btnChatbot.addEventListener('click', function () {
            chatbotPanel.classList.toggle('hidden');
        });

        function addMessage(text, sender = 'bot') {
            const div = document.createElement('div');

            if (sender === 'user') {
                div.className = 'bg-blue-600 text-white p-3 rounded-xl ml-8 text-xs leading-relaxed';
                div.innerText = text;
            } else {
                div.className = 'bg-white p-3 rounded-xl border text-slate-600 text-xs leading-relaxed';
                div.innerHTML = text;
            }

            chatMessages.appendChild(div);
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }

        chatbotForm.addEventListener('submit', function (e) {
            e.preventDefault();

            const message = chatInput.value.trim();

            if (!message) return;

            addMessage(message, 'user');
            chatInput.value = '';

            addMessage('SIPKOS AI sedang mengetik...', 'bot');

            fetch("{{ route('chatbot.ask') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    message: message
                })
            })
                .then(async res => {
                    const text = await res.text();

                    chatMessages.lastChild.remove();

                    let data;

                    try {
                        data = JSON.parse(text);
                    } catch (e) {
                        addMessage("Response bukan JSON. Status: " + res.status + " | Isi error: " + text.substring(0, 1000));
                        return;
                    }

                    addMessage(data.reply || 'Maaf, saya belum bisa menjawab.');
                })
                .catch((error) => {
                    chatMessages.lastChild.remove();
                    addMessage('Error JavaScript: ' + error.message);
                });
        });
    </script>
    @yield('scripts')

</body>

</html>