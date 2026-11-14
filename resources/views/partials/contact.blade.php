@php $t = $portfolio['texts'] ?? []; $b = $portfolio['brand']; @endphp
<section class="section section-alt pc-contact" id="contact">
    <div class="container">
        <div class="pp-section-head reveal">
            <span class="pp-eyebrow"><span class="pp-eyebrow-line"></span>Contact</span>
            <h2 class="pp-title">{{ $t['contact_title'] ?? "Let's build something together" }}</h2>
            <p class="pp-lead">{{ $t['contact_lead'] ?? '' }}</p>
        </div>

        <div class="pk-grid">
            <div class="pk-info reveal">
                <div class="pk-quick">
                    <button type="button" class="pk-quick-card is-live" data-pw-open="live">
                        <span class="pk-live-dot"></span>
                        <strong>Chat with me live</strong>
                        <small>Goes straight to my phone. I reply right in the chat.</small>
                    </button>
                    <button type="button" class="pk-quick-card" data-pw-open="assist">
                        <span class="pk-ai">AI</span>
                        <strong>Ask my AI assistant</strong>
                        <small>Instant answers about my projects and skills.</small>
                    </button>
                </div>

                <ul class="pk-list">
                    <li><span class="pk-ic"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg></span><div><small>Email</small><a href="mailto:{{ $b['email'] }}">{{ $b['email'] }}</a></div></li>
                    @if(!empty($b['phone']))<li><span class="pk-ic"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6A19.79 19.79 0 0 1 2.12 4.18 2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.36 1.9.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.91.34 1.85.57 2.81.7A2 2 0 0 1 22 16.92z"/></svg></span><div><small>Phone</small><a href="tel:{{ preg_replace('/[^\d+]/', '', $b['phone']) }}">{{ $b['phone'] }}</a></div></li>@endif
                    <li><span class="pk-ic"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg></span><div><small>Location</small><span>{{ $b['location'] }}</span></div></li>
                </ul>

                <div class="pk-socials">
                    @include('partials.social-icons', ['class' => 'pk-social', 'withLabel' => true])
                </div>
            </div>

            <div class="contact-form-card pk-form reveal" style="--reveal-delay: 120ms">
                <form id="contact-form" action="{{ route('contact.submit') }}" method="POST" class="contact-form">
                    @csrf
                    <div style="display:none;" aria-hidden="true">
                        <label for="_hp_title">Leave this empty</label>
                        <input type="text" id="_hp_title" name="_hp_title" tabindex="-1" autocomplete="off">
                    </div>
                    <div id="contact-feedback" class="form-feedback" role="alert"></div>
                    @if(session('contact_success'))
                        <div class="form-feedback success" style="display:block;">{{ session('contact_success') }}</div>
                    @endif
                    <h3 class="pk-form-title">Send a message</h3>
                    <div class="pk-row">
                        <div class="form-group"><label for="name" class="form-label">Your name *</label><input type="text" id="name" name="name" class="form-input" placeholder="Alex Morgan" required minlength="2" maxlength="100" autocomplete="name"></div>
                        <div class="form-group"><label for="email" class="form-label">Email *</label><input type="email" id="email" name="email" class="form-input" placeholder="alex@company.com" required maxlength="150" autocomplete="email"></div>
                    </div>
                    <div class="form-group"><label for="subject" class="form-label">Subject</label><input type="text" id="subject" name="subject" class="form-input" placeholder="Software engineering opportunity" maxlength="200"></div>
                    <div class="form-group"><label for="message" class="form-label">Message *</label><textarea id="message" name="message" class="form-textarea" rows="5" placeholder="Tell me a little about the role or project..." required minlength="10" maxlength="3000"></textarea></div>
                    <button type="submit" class="btn btn-primary pk-submit">
                        <span>Send message</span>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                    </button>
                    <p class="pk-note">Usually replies within a day. Your details are only used to reply to you.</p>
                </form>
            </div>
        </div>
    </div>
</section>
