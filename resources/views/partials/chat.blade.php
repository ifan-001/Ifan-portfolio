{{-- AI ASSISTANT + LIVE CHAT FLOATING WIDGET --}}
@php $chatAvatar = asset(ltrim($portfolio['brand']['profile_image'], '/')); @endphp
<div id="live-chat-widget" class="live-chat-widget pw-widget">
    {{-- Floating trigger --}}
    <button id="chat-bubble-btn" class="chat-bubble-btn pw-launcher" aria-label="Open AI assistant and live chat" aria-expanded="false">
        <span class="pw-launcher-ring" aria-hidden="true"></span>
        <span id="chat-status-indicator" class="chat-status-indicator online" title="Hasnain is online">
            <span class="chat-status-ping"></span>
        </span>
        <svg id="chat-icon-open" class="chat-icon-open" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M12 3l1.9 4.6L18.5 9.5l-4.6 1.9L12 16l-1.9-4.6L5.5 9.5l4.6-1.9z"></path>
            <path d="M19 15l.9 2.1L22 18l-2.1.9L19 21l-.9-2.1L16 18l2.1-.9z"></path>
            <path d="M5 16l.6 1.4L7 18l-1.4.6L5 20l-.6-1.4L3 18l1.4-.6z"></path>
        </svg>
        <svg id="chat-icon-close" class="chat-icon-close" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" aria-hidden="true" style="display: none;">
            <line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line>
        </svg>
        <span class="pw-launcher-hint" aria-hidden="true">Ask me anything</span>
    </button>

    {{-- Panel --}}
    <div id="chat-window" class="chat-window pw-panel" role="dialog" aria-labelledby="chat-header-name" aria-modal="false" style="display: none;">
        <div class="pw-header">
            <div class="pw-header-glow" aria-hidden="true"></div>
            <div class="chat-header-user">
                <div class="chat-avatar-wrapper pw-avatar">
                    <img src="{{ $chatAvatar }}" alt="Hasnain Al Ifan" class="chat-avatar-img">
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
                @auth
                <button type="button" id="admin-status-toggle" class="chat-action-btn" title="Toggle my Active / Away status" aria-label="Toggle online status">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M18.36 6.64a9 9 0 1 1-12.73 0"></path><line x1="12" y1="2" x2="12" y2="12"></line></svg>
                </button>
                @endauth
                <button type="button" id="chat-close-btn" class="chat-action-btn" aria-label="Close">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>
        </div>

        <div class="pw-tabs" role="tablist" aria-label="Assistant or live chat">
            <button type="button" class="pw-tab active" role="tab" aria-selected="true" data-pw-tab="assist">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 3l1.9 4.6L18.5 9.5l-4.6 1.9L12 16l-1.9-4.6L5.5 9.5l4.6-1.9z"></path></svg>
                AI Assistant
            </button>
            <button type="button" class="pw-tab" role="tab" aria-selected="false" data-pw-tab="live">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
                Message Hasnain
                <span class="pw-tab-dot" aria-hidden="true"></span>
            </button>
        </div>

        {{-- AI assistant pane --}}
        <div class="pw-pane active" data-pw-pane="assist" role="tabpanel">
            <div id="assist-body" class="pw-stream" aria-live="polite">
                <div class="pw-msg bot">
                    <div class="pw-bot-icon" aria-hidden="true">AI</div>
                    <div class="pw-bubble">
                        <p>Hi! I'm Hasnain's portfolio assistant. Ask me about his <strong>projects</strong>, any <strong>feature</strong>, his <strong>skills</strong>, <strong>experience</strong> or how to <strong>contact</strong> him. বাংলায়ও জিজ্ঞেস করতে পারেন।</p>
                    </div>
                </div>
                <div class="pw-chips" id="assist-chips">
                    <button type="button" class="pw-chip">What projects has he built?</button>
                    <button type="button" class="pw-chip">DoctorQR features</button>
                    <button type="button" class="pw-chip">Does he know Laravel?</button>
                    <button type="button" class="pw-chip">কীভাবে যোগাযোগ করব?</button>
                </div>
            </div>
            <form id="assist-form" class="pw-input" autocomplete="off">
                <input type="text" id="assist-input" maxlength="500" placeholder="Ask about projects, features, skills..." aria-label="Ask the assistant" required>
                <button type="submit" class="pw-send" aria-label="Send question">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
                </button>
            </form>
            <div class="pw-foot">Answers come from this portfolio's own data &middot; no third-party AI</div>
        </div>

        {{-- Live chat pane (logic in portfolio.js) --}}
        <div class="pw-pane" data-pw-pane="live" role="tabpanel">
            <div id="chat-status-banner" class="chat-status-banner online">
                <span class="status-banner-dot"></span>
                <span id="chat-status-banner-text">Hasnain is currently online &amp; ready to chat.</span>
            </div>
            <div id="chat-messages-body" class="chat-messages-body pw-stream">
                <div class="chat-message-row admin">
                    <div class="chat-msg-avatar"><img src="{{ $chatAvatar }}" alt="Hasnain Al Ifan"></div>
                    <div class="chat-bubble admin">
                        <p id="chat-dynamic-welcome">Hello! 👋 Thanks for visiting. Send me a direct message about a role, a project or a collaboration.</p>
                        <span class="chat-msg-time">Just now</span>
                    </div>
                </div>
            </div>
            <div id="chat-visitor-meta" class="chat-visitor-meta">
                <input type="text" id="chat-visitor-name" class="chat-meta-input" placeholder="Your name (optional)" maxlength="100">
                <input type="email" id="chat-visitor-email" class="chat-meta-input" placeholder="Email for replies" maxlength="150">
            </div>
            <form id="chat-input-form" class="chat-input-form pw-input">
                <input type="text" id="chat-text-input" class="chat-text-input" placeholder="Type a message..." autocomplete="off" maxlength="2000" required>
                <button type="submit" id="chat-send-btn" class="chat-send-btn pw-send" aria-label="Send message">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
                </button>
            </form>
            <div class="pw-foot">Goes straight to Hasnain's inbox &middot; he gets a phone alert</div>
        </div>
    </div>
</div>
