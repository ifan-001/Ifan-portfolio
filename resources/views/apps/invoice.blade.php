@extends('layouts.app')

@section('title', 'Free Invoice Generator (BDT, USD) | Hasnain Al Ifan')
@section('meta_description', 'Create a clean, professional invoice in your browser. Add items, tax and discount, pick a currency and download as PDF. Free and private.')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/cv-builder.css') }}?v={{ filemtime(public_path('css/cv-builder.css')) }}">
    <link rel="stylesheet" href="{{ asset('css/doc-apps.css') }}?v={{ filemtime(public_path('css/doc-apps.css')) }}">
@endpush

@section('content')
<div class="cvb">
    <header class="cvb-top no-print">
        <a href="{{ route('apps.index') }}" class="cvb-back">&larr; Apps &amp; Tools</a>
        <div class="cvb-heading">
            <h1>Invoice Generator</h1>
            <p>Fill in who it's from, who it's for and what you sold. Totals are calculated for you.</p>
        </div>
        <div class="cvb-top-actions">
            <button type="button" class="cvb-btn" id="iv-sample">Example</button>
            <button type="button" class="cvb-btn" id="iv-clear">Clear</button>
            <button type="button" class="cvb-btn cvb-btn-primary" onclick="window.print()">Download PDF</button>
        </div>
    </header>

    <div class="cvb-main">
        <form class="cvb-form no-print" id="iv-form" onsubmit="return false" autocomplete="off">
            <fieldset>
                <legend><span class="dx-step">1</span> Invoice</legend>
                <div class="cvb-grid">
                    <label>Invoice number<input data-k="number"></label>
                    <label>Currency
                        <select data-k="currency"><option value="৳">BDT (৳)</option><option value="$">USD ($)</option><option value="€">EUR (€)</option><option value="£">GBP (£)</option><option value="₹">INR (₹)</option></select>
                    </label>
                    <label>Date<input type="date" data-k="date"></label>
                    <label>Due date<input type="date" data-k="due"></label>
                </div>
            </fieldset>
            <fieldset>
                <legend><span class="dx-step">2</span> From &amp; to</legend>
                <label>Your business / name<input data-k="from" placeholder="Pixel Studio"></label>
                <label>Your address, email, phone<textarea data-k="fromInfo" rows="2"></textarea></label>
                <label>Bill to<input data-k="to" placeholder="Client name"></label>
                <label>Client address, email<textarea data-k="toInfo" rows="2"></textarea></label>
            </fieldset>
            <fieldset>
                <legend><span class="dx-step">3</span> Items <button type="button" class="cvb-add" id="iv-add">+ Add item</button></legend>
                <div id="iv-items" class="cvb-items"></div>
                <div class="cvb-grid">
                    <label>Tax / VAT %<input type="number" min="0" max="100" step="0.01" data-k="tax"></label>
                    <label>Discount<input type="number" min="0" step="0.01" data-k="discount"></label>
                </div>
                <label>Notes / payment details<textarea data-k="notes" rows="3" placeholder="bKash: 01XXXXXXXXX · Thank you for your business!"></textarea></label>
            </fieldset>
            <p class="cvb-note">Saved in this browser only.</p>
        </form>

        <div class="cvb-preview-wrap dx-preview">
            <article class="cv-paper dx-invoice" id="cv-paper"></article>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function () {
    var KEY = 'iv_state_v1', form = document.getElementById('iv-form'), paper = document.getElementById('cv-paper'), list = document.getElementById('iv-items');
    var today = new Date(), iso = function (d) { return d.toISOString().slice(0, 10); };
    var EMPTY = { number: 'INV-' + today.getFullYear() + '-001', currency: '৳', date: iso(today), due: iso(new Date(today.getTime() + 14 * 864e5)), from: '', fromInfo: '', to: '', toInfo: '', tax: 0, discount: 0, notes: '', items: [{ desc: '', qty: 1, price: 0 }] };
    var state; try { state = JSON.parse(localStorage.getItem(KEY)); } catch (e) {}
    state = state && state.items ? state : JSON.parse(JSON.stringify(EMPTY));
    var esc = function (s) { var d = document.createElement('div'); d.textContent = s == null ? '' : s; return d.innerHTML; };
    var money = function (n) { return state.currency + ' ' + (Math.round(n * 100) / 100).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }); };

    function bind() { form.querySelectorAll('[data-k]').forEach(function (el) { el.value = state[el.dataset.k] == null ? '' : state[el.dataset.k]; }); drawItems(); render(); }
    function save() { try { localStorage.setItem(KEY, JSON.stringify(state)); } catch (e) {} }
    form.addEventListener('input', function (e) {
        var el = e.target;
        if (el.dataset.k) state[el.dataset.k] = el.type === 'number' ? parseFloat(el.value) || 0 : el.value;
        if (el.dataset.i !== undefined) { var it = state.items[+el.dataset.i]; it[el.dataset.f] = el.dataset.f === 'desc' ? el.value : parseFloat(el.value) || 0; }
        render(); save();
    });
    form.addEventListener('change', function (e) { if (e.target.tagName === 'SELECT') { state[e.target.dataset.k] = e.target.value; render(); save(); } });
    function drawItems() {
        list.innerHTML = state.items.map(function (it, i) {
            return '<div class="cvb-item dx-item"><div class="cvb-item-head"><span>Item ' + (i + 1) + '</span><button type="button" data-rm="' + i + '">Remove</button></div>' +
                '<label>Description<input data-i="' + i + '" data-f="desc" value="' + esc(it.desc) + '"></label>' +
                '<div class="cvb-grid"><label>Qty<input type="number" min="0" step="any" data-i="' + i + '" data-f="qty" value="' + it.qty + '"></label><label>Price<input type="number" min="0" step="any" data-i="' + i + '" data-f="price" value="' + it.price + '"></label></div></div>';
        }).join('');
    }
    list.addEventListener('click', function (e) { var b = e.target.closest('[data-rm]'); if (b) { state.items.splice(+b.dataset.rm, 1); if (!state.items.length) state.items.push({ desc: '', qty: 1, price: 0 }); drawItems(); render(); save(); } });
    document.getElementById('iv-add').addEventListener('click', function () { state.items.push({ desc: '', qty: 1, price: 0 }); drawItems(); render(); save(); });

    function fmtDate(v) { if (!v) return ''; var d = new Date(v); return isNaN(d) ? v : d.toLocaleDateString('en-GB', { day: 'numeric', month: 'short', year: 'numeric' }); }
    function render() {
        var sub = state.items.reduce(function (s, it) { return s + (it.qty || 0) * (it.price || 0); }, 0);
        var tax = sub * (state.tax || 0) / 100, total = Math.max(0, sub + tax - (state.discount || 0));
        paper.innerHTML =
            '<header class="dx-inv-head"><div><div class="dx-inv-brand">' + (esc(state.from) || 'Your Business') + '</div><div class="dx-muted">' + esc(state.fromInfo).replace(/\n/g, '<br>') + '</div></div>' +
            '<div class="dx-inv-meta"><h2>INVOICE</h2><div><span>No.</span>' + esc(state.number) + '</div><div><span>Date</span>' + fmtDate(state.date) + '</div><div><span>Due</span>' + fmtDate(state.due) + '</div></div></header>' +
            '<section class="dx-billto"><span>Bill to</span><strong>' + (esc(state.to) || 'Client name') + '</strong><div class="dx-muted">' + esc(state.toInfo).replace(/\n/g, '<br>') + '</div></section>' +
            '<table class="dx-table"><thead><tr><th>Description</th><th>Qty</th><th>Price</th><th>Amount</th></tr></thead><tbody>' +
            state.items.map(function (it) { return '<tr><td>' + (esc(it.desc) || '<span class="dx-muted">Item</span>') + '</td><td>' + (it.qty || 0) + '</td><td>' + money(it.price || 0) + '</td><td>' + money((it.qty || 0) * (it.price || 0)) + '</td></tr>'; }).join('') +
            '</tbody></table>' +
            '<div class="dx-totals"><div><span>Subtotal</span>' + money(sub) + '</div>' + (state.tax ? '<div><span>Tax (' + state.tax + '%)</span>' + money(tax) + '</div>' : '') + (state.discount ? '<div><span>Discount</span>− ' + money(state.discount) + '</div>' : '') + '<div class="dx-grand"><span>Total due</span>' + money(total) + '</div></div>' +
            (state.notes ? '<section class="dx-notes"><span>Notes</span><p>' + esc(state.notes).replace(/\n/g, '<br>') + '</p></section>' : '');
    }
    document.getElementById('iv-sample').addEventListener('click', function () {
        state = Object.assign(JSON.parse(JSON.stringify(EMPTY)), { from: 'Pixel Studio', fromInfo: 'House 12, Road 5, Dhanmondi, Dhaka\nhello@pixelstudio.com · +880 1712-000000', to: 'TechNova Ltd.', toInfo: 'Gulshan-2, Dhaka\naccounts@technova.com', tax: 5, discount: 1000, notes: 'Payment by bKash or bank transfer within 14 days.\nThank you for your business!', items: [{ desc: 'Website design & development', qty: 1, price: 45000 }, { desc: 'Admin panel (Laravel)', qty: 1, price: 30000 }, { desc: 'Monthly maintenance', qty: 3, price: 5000 }] });
        bind(); save();
    });
    document.getElementById('iv-clear').addEventListener('click', function () { if (confirm('Clear the invoice?')) { state = JSON.parse(JSON.stringify(EMPTY)); bind(); save(); } });
    if (!state.from && state.items.length === 1 && !state.items[0].desc) document.getElementById('iv-sample').click(); else bind();
})();
</script>
@endpush
