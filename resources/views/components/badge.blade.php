@php $iconTrailing = $iconTrailing ??= $attributes->pluck('icon:trailing'); @endphp
@php $iconVariant = $iconVariant ??= $attributes->pluck('icon:variant'); @endphp

@props([
     'status' => null,
     'iconVariant' => 'micro',
     'iconTrailing' => null,
     'variant' => null,
     'inset' => null,
     'size' => null,
     'icon' => null,
])
@php
//'pending','submitted', 'verified', 'rejected', 'closed', 'cancelled', 'accepted'
    if (isset($status)) {
        $result = match ($status) {
            'draft' => ['zinc', 'Draft'],
            'pending' => ['yellow', 'Menunggu'],
            'submitted' => ['blue', 'Dikirim'],
            'verified' => ['lime', 'Diverifikasi'],
            'rejected' => ['red', 'Ditolak'],
            'closed' => ['zinc', 'Ditutup'],
            'cancelled' => ['rose', 'Dibatalkan'],
            'accepted' => ['emerald', 'Diterima'],
            'approved' => ['green', 'Disetujui'],
            'success' => ['green', 'Berhasil'],
            'danger' => ['red', 'Gagal'],
            'warning' => ['yellow', 'Peringatan'],
            'info' => ['blue', 'Informasi'],
            'revision' => ['orange', 'Revisi'],
            'revised' => ['orange', 'Direvisi'],
            default => [null, null],
        };

    [$color, $name] = $result;
}

@endphp
<flux:badge :color="$color" :size="$size" :inset="$inset" :variant="$variant" :icon="$icon" :icon-trailing="$iconTrailing">
    {{ $name }}
</flux:badge>
