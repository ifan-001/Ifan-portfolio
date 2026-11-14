{{-- LIVE CHAT FLOATING BUBBLE & MODAL SYSTEM --}}
<div id="live-chat-widget" class="live-chat-widget">
    {{-- Floating Trigger Button --}}
    <button id="chat-bubble-btn" class="chat-bubble-btn" aria-label="Open Live Chat" aria-expanded="false">
        {{-- Status Indicator Dot (Green = Online, Red = Offline) --}}
        <span id="chat-status-indicator" class="chat-status-indicator online" title="Status: Active Now">
            <span class="chat-status-ping"></span>
        </span>

        {{-- Chat Open Icon --}}
        <svg id="chat-icon-open" class="chat-icon-open" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
        </svg>

        {{-- Chat Close Icon --}}
        <svg id="chat-icon-close" class="chat-icon-close" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" style="display: none;">
            <line x1="18" y1="6" x2="6" y2="18"></line>
            <line x1="6" y1="6" x2="18" y2="18"></line>
        </svg>
    </button>

    {{-- Chat Window Modal --}}
    <div id="chat-window" class="chat-window" role="dialog" aria-labelledby="chat-header-name" aria-modal="true" style="display: none;">
        {{-- Header --}}
        <div class="chat-header">
            <div class="chat-header-user">
                <div class="chat-avatar-wrapper">
                    <img src="{{ asset(ltrim($portfolio['brand']['profile_image'], '/')) }}" alt="Hasnain Al Ifan" class="chat-avatar-img">
                    <span id="chat-header-status-dot" class="chat-header-status-dot online"></span>
                </div>
                <div>
                    <div id="chat-header-name" class="chat-header-name">Hasnain Al Ifan</div>
                    <div id="chat-header-status-text" class="chat-header-status-text">
                        <span class="status-text-pill online">Active Now</span>
                    </div>
                </div>
            </div>

            <div class="chat-header-actions">
                {{-- Quick Admin Status Toggle (authenticated admin only) --}}
                @auth
                <button type="button" id="admin-status-toggle" class="chat-action-btn" title="Toggle My Active/Offline Status" aria-label="Toggle Online Status">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="3"></circle>
                        <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
                    </svg>
                </button>
                @endauth

                <button type="button" id="chat-close-btn" class="chat-action-btn" aria-label="Close Chat">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>
        </div>

        {{-- Status Notification Banner --}}
        <div id="chat-status-banner" class="chat-status-banner online">
            <span class="status-banner-dot"></span>
            <span id="chat-status-banner-text">Hasnain is currently online &amp; ready to chat.</span>
        </div>

        {{-- Messages Stream --}}
        <div id="chat-messages-body" class="chat-messages-body">
            {{-- Default System Welcome Message --}}
            <div class="chat-message-row admin">
                <div class="chat-msg-avatar">
                    <img src="{{ asset(ltrim($portfolio['brand']['profile_image'], '/')) }}" alt="Hasnain Al Ifan">
                </div>
                <div class="chat-bubble admin">
                    <p id="chat-dynamic-welcome">
                        Hello! 👋 Thanks for visiting my portfolio. I'm currently active—feel free to send me a direct message about web development, Laravel, or collaboration!
                    </p>
                    <span class="chat-msg-time">Just now</span>
                </div>
            </div>
        </div>

        {{-- Visitor Info Sub-row (Pre-filled or expandable) --}}
        <div id="chat-visitor-meta" class="chat-visitor-meta">
            <input type="text" id="chat-visitor-name" class="chat-meta-input" placeholder="Your Name (Optional)">
            <input type="email" id="chat-visitor-email" class="chat-meta-input" placeholder="Your Email (For replies)">
        </div>

        {{-- Input Footer --}}
        <form id="chat-input-form" class="chat-input-form">
            <input type="text" id="chat-text-input" class="chat-text-input" placeholder="Type a message..." autocomplete="off" required>
            <button type="submit" id="chat-send-btn" class="chat-send-btn" aria-label="Send Message">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="22" y1="2" x2="11" y2="13"></line>
                    <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                </svg>
            </button>
        </form>

        <div class="chat-window-footer">
            <span>Direct Live Chat &bull; Connected to Hasnain's Backend API</span>
        </div>
    </div>
</div>
