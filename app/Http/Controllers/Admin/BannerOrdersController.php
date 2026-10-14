<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BannerOrder;
use App\Services\BannerOrderService;
use Illuminate\Http\Request;

/**
 * Pedidos de banners por autoservicio: aprobar el pago de una transferencia y
 * revisar la imagen antes de que salga al aire.
 */
class BannerOrdersController extends Controller
{
    public function index()
    {
        $orders = BannerOrder::with(['banner', 'user'])->latest()->get();

        return view('admin.banner_orders.index', compact('orders'));
    }

    public function approvePayment(BannerOrder $order, BannerOrderService $service)
    {
        abort_unless($order->payment_method === BannerOrder::PAY_TRANSFER, 404);

        $order->update(['payment_status' => BannerOrder::APPROVED]);
        $service->activateIfReady($order);

        return back()->with('success', 'Pago aprobado.');
    }

    public function rejectPayment(Request $request, BannerOrder $order, BannerOrderService $service)
    {
        $reason = (string) $request->input('reason');
        $order->update(['payment_status' => BannerOrder::REJECTED, 'rejection_reason' => $reason ?: null]);

        \App\Support\BrandedMail::withLocale($order->user->locale ?: 'es', function () use ($service, $order, $reason) {
            $service->mail(
                $order->user->email,
                __('mail.banner_pay_subject'),
                __('mail.banner_pay_heading'),
                array_values(array_filter([
                    __('mail.banner_pay_body', ['name' => $order->banner->name]),
                    $reason ? __('mail.rejected_reason', ['reason' => $reason]) : null,
                    __('mail.banner_pay_contact'),
                ]))
            );
        });

        return back()->with('success', 'Pago rechazado.');
    }

    public function approveReview(BannerOrder $order, BannerOrderService $service)
    {
        $order->update(['review_status' => BannerOrder::APPROVED]);
        $service->activateIfReady($order);

        return back()->with('success', $order->fresh()->activated_at
            ? 'Banner aprobado y publicado.'
            : 'Imagen aprobada. Sale al aire cuando el pago esté aprobado.');
    }

    public function rejectReview(Request $request, BannerOrder $order, BannerOrderService $service)
    {
        $reason = (string) $request->input('reason');
        $order->update(['review_status' => BannerOrder::REJECTED, 'rejection_reason' => $reason ?: null]);
        $order->banner->update(['is_active' => false]);

        \App\Support\BrandedMail::withLocale($order->user->locale ?: 'es', function () use ($service, $order, $reason) {
            $service->mail(
                $order->user->email,
                __('mail.banner_img_subject'),
                __('mail.banner_img_heading'),
                array_values(array_filter([
                    __('mail.banner_img_body', ['name' => $order->banner->name]),
                    $reason ? __('mail.rejected_reason', ['reason' => $reason]) : null,
                    $order->payment_status === \App\Models\BannerOrder::APPROVED ? __('mail.banner_img_refund') : null,
                ]))
            );
        });

        return back()->with('success', 'Banner rechazado. Si ya pagó, hay que devolverle el pago.');
    }
}
