<?php

namespace App\Http\Controllers;

use App\Models\Kos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChatbotController extends Controller
{
    public function ask(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:500',
        ]);

        $message = trim($request->message);

        try {
            $apiKey = config('services.openrouter.key');
            $model = config('services.openrouter.model', 'openrouter/free');

            if (!$apiKey) {
                return response()->json([
                    'reply' => 'API Key OpenRouter belum dipasang di file .env.',
                ], 500);
            }

            $kosData = Kos::select(
                'id',
                'nama',
                'lokasi',
                'harga',
                'tipe_kos',
                'ac',
                'wifi',
                'kamar_mandi_dalam'
            )
                ->orderBy('harga', 'asc')
                ->limit(15)
                ->get()
                ->map(function ($kos) {
                    return [
                        'nama' => $kos->nama,
                        'lokasi' => $kos->lokasi,
                        'harga' => 'Rp ' . number_format($kos->harga, 0, ',', '.'),
                        'tipe_kos' => $kos->tipe_kos,
                        'fasilitas' => collect([
                            $kos->ac ? 'AC' : null,
                            $kos->wifi ? 'Wifi' : null,
                            $kos->kamar_mandi_dalam ? 'Kamar Mandi Dalam' : null,
                        ])->filter()->values()->implode(', ') ?: 'Fasilitas standar',
                        'detail_url' => route('kos.show', $kos->id),
                        'booking_url' => route('kos.booking', $kos->id),
                    ];
                })
                ->values()
                ->toArray();

            $systemPrompt = "
Kamu adalah SIPKOS AI, chatbot website pencarian kos.

Kamu hanya boleh menjawab seputar:
- pencarian kos
- rekomendasi kos
- fasilitas kos
- harga kos
- lokasi kos
- cara booking
- pembayaran
- jasa pindahan
- kontak pemilik

Jika pertanyaan di luar topik, jawab:
Mohon maaf, saya hanya bisa membantu pertanyaan seputar pencarian kos, booking, pembayaran, fasilitas, lokasi, jasa pindahan, dan kontak pemilik di SIPKOS.

Gunakan hanya data kos yang diberikan.
Jangan mengarang data kos.
Tampilkan maksimal 5 rekomendasi kos.
Gunakan bahasa Indonesia yang ramah dan singkat.
Jika ada link, gunakan HTML:
<a href='URL'>Lihat Detail</a> | <a href='URL'>Booking</a>
";

            $userPrompt = "
Data kos SIPKOS:
" . json_encode($kosData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "

Pertanyaan user:
" . $message;

            $response = Http::timeout(60)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $apiKey,
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                    'HTTP-Referer' => url('/'),
                    'X-Title' => 'SIPKOS AI',
                ])
                ->post('https://openrouter.ai/api/v1/chat/completions', [
                    'model' => $model,
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => $systemPrompt,
                        ],
                        [
                            'role' => 'user',
                            'content' => $userPrompt,
                        ],
                    ],
                    'temperature' => 0.3,
                    'max_tokens' => 700,
                ]);

            if (!$response->successful()) {
                return response()->json([
                    'reply' => 'OpenRouter Error: ' . $response->status() . ' | ' . e($response->body()),
                ], 500);
            }

            $data = $response->json();

            $reply = $data['choices'][0]['message']['content']
                ?? null;

            if (!$reply) {
                return response()->json([
                    'reply' => 'OpenRouter berhasil dihubungi, tetapi tidak mengirim jawaban. Coba ganti model ke openrouter/free.',
                ], 500);
            }

            return response()->json([
                'reply' => nl2br($reply),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'reply' => 'ERROR: ' . $e->getMessage(),
            ], 500);
        }
    }
}