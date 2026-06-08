<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Review;
use App\Rules\NoProfanity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request, $productSlug)
    {
        $product = Product::where('slug', $productSlug)->firstOrFail();
        $ip = $request->ip();

        // ── 1. Bloqueo por IP reincidente ────────────────────────
        if (Review::isIpBlocked($ip)) {
            return back()->withErrors([
                'content' => 'No podés enviar más comentarios desde esta sesión.',
            ]);
        }

        // ── 2. Validación básica ──────────────────────────────────
        $validated = $request->validate([
            'content'     => ['required', 'string', 'min:5'],
            'rating'      => ['required', 'integer', 'min:1', 'max:5'],
            'author_name' => ['nullable', 'string', 'max:50'],
        ]);

        // ── 3. Filtro de profanidad → auto-rechazo silencioso ─────
        $hasProfanity = $this->detectProfanity($validated['content']);

        $review = new Review($validated);
        $review->product_id  = $product->id;
        $review->ip_address  = $ip;
        $review->is_approved = false;

        if (Auth::check()) {
            $review->user_id     = Auth::id();
            $review->author_name = Auth::user()->name;
        } else {
            $review->author_name = $validated['author_name'] ?? 'Anónimo';
        }

        $review->status  = Review::STATUS_PENDING;
        $review->flagged = $hasProfanity; // si tiene palabras feas: marcado, no se auto-publica

        $review->save();

        // Siempre el mismo mensaje — el visitante no sabe si fue marcado o no
        return back()->with('review_sent', true);
    }

    // ── Helpers ──────────────────────────────────────────────────

    private function detectProfanity(string $text): bool
    {
        $normalized = $this->normalize($text);

        $words = [
            'puto','puta','putas','putos','putear','puteo',
            'mierda','mierdas','carajo','carajos',
            'concha','conchas',
            'boludo','boluda','boludos','boludas','boludez',
            'pelotudo','pelotuda','pelotudos','pelotudas',
            'cagada','cagado','cagar',
            'idiota','idiotas','imbecil',
            'estupido','estupida',
            'tonto','tonta','tontos','tontas',
            'hdp','forro','forra','forros',
            'mogolico','tarado','tarada','gil','giles','cretino','cretina',
            'culo','culos','pija','pijas','verga','vergas','cono','joder',
            // Portugués
            'merda','porra','caralho','foda','fodase','buceta','viado',
            // Inglés
            'fuck','fucking','fucker','shit','asshole','bitch','cunt','bastard',
            'dick','cock','pussy',
            // Francés
            'merde','putain','connard','salaud',
        ];

        foreach ($words as $word) {
            if (preg_match('/\b' . preg_quote($this->normalize($word), '/') . '\b/u', $normalized)) {
                return true;
            }
        }

        return false;
    }

    private function normalize(string $text): string
    {
        $text = mb_strtolower($text);
        return str_replace(
            ['á','é','í','ó','ú','ü','ñ','à','â','ê','î','ô','û','ç'],
            ['a','e','i','o','u','u','n','a','a','e','i','o','u','c'],
            $text
        );
    }
}
