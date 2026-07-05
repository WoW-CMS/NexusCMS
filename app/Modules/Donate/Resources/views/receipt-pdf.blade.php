<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Donation Receipt #{{ $tx->id }}</title>
    <style>
        @page { margin: 36px; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #1f2937; }
        .header { border-bottom: 2px solid #2563eb; padding-bottom: 16px; margin-bottom: 24px; }
        .header h1 { margin: 0 0 4px; font-size: 22px; color: #111827; }
        .header p  { margin: 0; color: #6b7280; font-size: 11px; }
        .meta { width: 100%; margin-bottom: 24px; border-collapse: collapse; }
        .meta td { padding: 8px 12px; border: 1px solid #e5e7eb; vertical-align: top; }
        .meta td.label { background: #f9fafb; font-weight: 600; width: 30%; color: #374151; }
        .meta td.value { color: #111827; }
        .amount { font-size: 24px; color: #2563eb; font-weight: 700; }
        .status-completed { color: #059669; font-weight: 600; }
        .status-pending   { color: #d97706; font-weight: 600; }
        .status-failed    { color: #dc2626; font-weight: 600; }
        .footer { margin-top: 40px; padding-top: 12px; border-top: 1px solid #e5e7eb; color: #6b7280; font-size: 10px; }
        .footer p { margin: 4px 0; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Donation Receipt</h1>
        <p>{{ config('app.name', 'NexusCMS') }} · Generated {{ now()->format('Y-m-d H:i') }} UTC</p>
    </div>

    <table class="meta">
        <tr>
            <td class="label">Receipt ID</td>
            <td class="value">#{{ $tx->id }}</td>
        </tr>
        <tr>
            <td class="label">Date</td>
            <td class="value">{{ $tx->created_at->format('Y-m-d H:i:s') }}</td>
        </tr>
        <tr>
            <td class="label">Donor</td>
            <td class="value">{{ $tx->user?->name ?? 'N/A' }} ({{ $tx->user?->email ?? '—' }})</td>
        </tr>
        <tr>
            <td class="label">Gateway</td>
            <td class="value">{{ ucfirst($tx->gateway) }}</td>
        </tr>
        <tr>
            <td class="label">Gateway Transaction ID</td>
            <td class="value">{{ $tx->transaction_id ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="label">Amount</td>
            <td class="value"><span class="amount">${{ number_format((float) $tx->amount, 2) }} {{ $tx->currency }}</span></td>
        </tr>
        <tr>
            <td class="label">Donation Points Awarded</td>
            <td class="value"><strong>{{ number_format((int) $tx->dp_awarded) }} DP</strong></td>
        </tr>
        <tr>
            <td class="label">Status</td>
            <td class="value">
                <span class="status-{{ $tx->status }}">{{ ucfirst($tx->status) }}</span>
            </td>
        </tr>
    </table>

    <div class="footer">
        <p>This receipt is your proof of donation. Please retain it for your records.</p>
        <p>Thank you for supporting {{ config('app.name', 'NexusCMS') }}.</p>
        <p>Receipt hash: {{ substr(hash('sha256', $tx->id . '|' . $tx->transaction_id . '|' . $tx->amount . '|' . config('app.key')), 0, 16) }}</p>
    </div>
</body>
</html>
