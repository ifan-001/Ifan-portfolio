<section class="section section-alt" id="contact">
    <div class="container">
        <div class="section-header">
            <span class="section-eyebrow">Get In Touch</span>
            <h2 class="section-title">Let's Build Something Meaningful.</h2>
            <p class="section-desc">
                I'm interested in building useful software, solving practical technical problems, and continuing to grow as an engineer. Feel free to connect with me about software development, technical collaboration, and professional opportunities.
            </p>
        </div>

        <div class="contact-grid">
            <div class="contact-info-col">
                <div class="contact-methods">
                    <a href="mailto:{{ $portfolio['brand']['email'] }}" class="contact-method-card" aria-label="Email Hasnain Al Ifan">
                        <div class="contact-method-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                                <polyline points="22,6 12,13 2,6"></polyline>
                            </svg>
                        </div>
                        <div>
                            <div class="contact-method-label">Direct Email</div>
                            <div class="contact-method-val">{{ $portfolio['brand']['email'] }}</div>
                        </div>
                    </a>

                    <div class="contact-method-card">
                        <div class="contact-method-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                <circle cx="12" cy="10" r="3"></circle>
                            </svg>
                        </div>
                        <div>
                            <div class="contact-method-label">Location</div>
                            <div class="contact-method-val">{{ $portfolio['brand']['location'] }}</div>
                        </div>
                    </div>

                    <a href="{{ $portfolio['socials']['linkedin']['url'] }}" target="_blank" rel="noopener noreferrer" class="contact-method-card" aria-label="LinkedIn Profile">
                        <div class="contact-method-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"></path>
                                <rect x="2" y="9" width="4" height="12"></rect>
                                <circle cx="4" cy="4" r="2"></circle>
                            </svg>
                        </div>
                        <div>
                            <div class="contact-method-label">LinkedIn</div>
                            <div class="contact-method-val">{{ $portfolio['socials']['linkedin']['handle'] }}</div>
                        </div>
                    </a>

                    <a href="{{ $portfolio['socials']['github']['url'] }}" target="_blank" rel="noopener noreferrer" class="contact-method-card" aria-label="GitHub Profile">
                        <div class="contact-method-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M9 19c-5 1.5-5-2.5-7-3m14 6v-3.87a3.37 3.37 0 0 0-.94-2.61c3.14-.35 6.44-1.54 6.44-7A5.44 5.44 0 0 0 20 4.77 5.07 5.07 0 0 0 19.91 1S18.73.65 16 2.48a13.38 13.38 0 0 0-7 0C6.27.65 5.09 1 5.09 1A5.07 5.07 0 0 0 5 4.77a5.44 5.44 0 0 0-1.5 3.78c0 5.42 3.3 6.61 6.44 7A3.37 3.37 0 0 0 9 18.13V22"></path>
                            </svg>
                        </div>
                        <div>
                            <div class="contact-method-label">GitHub</div>
                            <div class="contact-method-val">{{ $portfolio['socials']['github']['handle'] }}</div>
                        </div>
                    </a>
                </div>
            </div>

            {{-- Contact Form --}}
            <div class="contact-form-card">
                <form id="contact-form" action="{{ route('contact.submit') }}" method="POST" class="contact-form">
                    @csrf

                    <div id="contact-feedback" class="form-feedback" role="alert"></div>

                    @if(session('contact_success'))
                        <div class="form-feedback success" style="display: block;">
                            {{ session('contact_success') }}
                        </div>
                    @endif

                    <div class="form-group">
                        <label for="name" class="form-label">Full Name *</label>
                        <input type="text" id="name" name="name" class="form-input" placeholder="e.g. Alex Morgan" required maxlength="100">
                    </div>

                    <div class="form-group">
                        <label for="email" class="form-label">Email Address *</label>
                        <input type="email" id="email" name="email" class="form-input" placeholder="e.g. alex@company.com" required maxlength="150">
                    </div>

                    <div class="form-group">
                        <label for="subject" class="form-label">Subject</label>
                        <input type="text" id="subject" name="subject" class="form-input" placeholder="e.g. Software Engineering Opportunity" maxlength="200">
                    </div>

                    <div class="form-group">
                        <label for="message" class="form-label">Message *</label>
                        <textarea id="message" name="message" class="form-textarea" placeholder="How can we collaborate?" required minlength="10" maxlength="3000"></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary" style="width: 100%;">
                        <span>Send Message</span>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <line x1="22" y1="2" x2="11" y2="13"></line>
                            <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>
