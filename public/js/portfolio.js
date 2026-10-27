/**
 * HASNAIN AL IFAN — PORTFOLIO CLIENT LOGIC
 * Lightweight, accessible, and fast.
 */

document.addEventListener('DOMContentLoaded', function () {
  'use strict';

  // 1. Mobile Menu Drawer Toggle
  const menuToggle = document.getElementById('menu-toggle');
  const mobileDrawer = document.getElementById('mobile-drawer');

  if (menuToggle && mobileDrawer) {
    menuToggle.addEventListener('click', function () {
      const isOpen = mobileDrawer.classList.toggle('open');
      menuToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
      if (isOpen) {
        document.body.style.overflow = 'hidden';
      } else {
        document.body.style.overflow = '';
      }
    });

    // Close mobile drawer when clicking any nav link
    const mobileLinks = mobileDrawer.querySelectorAll('a');
    mobileLinks.forEach(function (link) {
      link.addEventListener('click', function () {
        mobileDrawer.classList.remove('open');
        menuToggle.setAttribute('aria-expanded', 'false');
        document.body.style.overflow = '';
      });
    });

    // Close on Escape key
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape' && mobileDrawer.classList.contains('open')) {
        mobileDrawer.classList.remove('open');
        menuToggle.setAttribute('aria-expanded', 'false');
        document.body.style.overflow = '';
      }
    });
  }

  // 2. Active Section Scroll Spy
  const sections = document.querySelectorAll('section[id]');
  const navLinks = document.querySelectorAll('.nav-link, .mobile-nav-link');

  function updateActiveNav() {
    const scrollY = window.pageYOffset || document.documentElement.scrollTop;
    const headerHeight = 90;

    sections.forEach(function (section) {
      const sectionHeight = section.offsetHeight;
      const sectionTop = section.offsetTop - headerHeight;
      const sectionId = section.getAttribute('id');

      if (scrollY >= sectionTop && scrollY < sectionTop + sectionHeight) {
        navLinks.forEach(function (link) {
          if (link.getAttribute('href') === '#' + sectionId) {
            link.classList.add('active');
          } else if (link.getAttribute('href').startsWith('#')) {
            link.classList.remove('active');
          }
        });
      }
    });
  }

  window.addEventListener('scroll', updateActiveNav, { passive: true });
  updateActiveNav();

  // 3. Project Filter Tabs
  const filterButtons = document.querySelectorAll('.filter-btn');
  const projectCards = document.querySelectorAll('[data-project-category]');

  if (filterButtons.length > 0 && projectCards.length > 0) {
    filterButtons.forEach(function (btn) {
      btn.addEventListener('click', function () {
        const filter = this.getAttribute('data-filter');

        filterButtons.forEach(function (b) {
          b.classList.remove('active');
          b.setAttribute('aria-pressed', 'false');
        });
        this.classList.add('active');
        this.setAttribute('aria-pressed', 'true');

        projectCards.forEach(function (card) {
          const category = card.getAttribute('data-project-category');
          if (filter === 'all' || category === filter) {
            card.style.display = '';
          } else {
            card.style.display = 'none';
          }
        });
      });
    });
  }

  // 4. DoctorQR Workflow Showcase Tabs
  const workflowTabs = document.querySelectorAll('.workflow-tab-btn');
  const workflowPanes = document.querySelectorAll('.workflow-pane');

  if (workflowTabs.length > 0 && workflowPanes.length > 0) {
    workflowTabs.forEach(function (tab) {
      tab.addEventListener('click', function () {
        const targetId = this.getAttribute('data-workflow-target');

        workflowTabs.forEach(function (t) {
          t.classList.remove('active');
          t.setAttribute('aria-selected', 'false');
        });
        workflowPanes.forEach(function (p) {
          p.classList.remove('active');
        });

        this.classList.add('active');
        this.setAttribute('aria-selected', 'true');

        const activePane = document.getElementById(targetId);
        if (activePane) {
          activePane.classList.add('active');
        }
      });
    });
  }

  // 5. Contact Form Submission Handling
  const contactForm = document.getElementById('contact-form');
  const contactFeedback = document.getElementById('contact-feedback');

  if (contactForm && contactFeedback) {
    contactForm.addEventListener('submit', function (e) {
      e.preventDefault();

      const submitBtn = contactForm.querySelector('button[type="submit"]');
      const originalBtnText = submitBtn ? submitBtn.innerHTML : 'Send Message';

      if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.innerHTML = 'Sending message...';
      }

      const formData = new FormData(contactForm);

      fetch(contactForm.action, {
        method: 'POST',
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
          'Accept': 'application/json',
        },
        body: formData,
      })
        .then(function (response) {
          return response.json();
        })
        .then(function (data) {
          if (data.status === 'success') {
            contactFeedback.className = 'form-feedback success';
            contactFeedback.innerHTML = `
              <strong>Success!</strong> ${data.message}
              <div style="margin-top: 8px;">
                <a href="${data.mailto_url}" style="color: #3B82F6; text-decoration: underline; font-weight: 600;">
                  Click here to also open in your email client &rarr;
                </a>
              </div>
            `;
            contactFeedback.style.display = 'block';
            contactForm.reset();
          } else {
            throw new Error(data.message || 'Submission error');
          }
        })
        .catch(function () {
          // Graceful fallback to client mailto
          const name = encodeURIComponent(formData.get('name') || '');
          const subject = encodeURIComponent(formData.get('subject') || 'Portfolio Inquiry');
          const message = encodeURIComponent(formData.get('message') || '');
          const fallbackMailto = `mailto:hasnainalifan23@gmail.com?subject=${subject}&body=From:%20${name}%0A%0A${message}`;

          contactFeedback.className = 'form-feedback success';
          contactFeedback.innerHTML = `
            Thank you! You can directly send your message via:
            <a href="${fallbackMailto}" style="color: #3B82F6; text-decoration: underline; font-weight: 600; margin-left: 4px;">
              hasnainalifan23@gmail.com
            </a>
          `;
          contactFeedback.style.display = 'block';
        })
        .finally(function () {
          if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalBtnText;
          }
        });
    });
  }

  // 6. Profile Image Error Fallback
  const profileImg = document.getElementById('hero-profile-image');
  const fallbackAvatar = document.getElementById('hero-fallback-avatar');

  if (profileImg && fallbackAvatar) {
    profileImg.addEventListener('error', function () {
      profileImg.style.display = 'none';
      fallbackAvatar.style.display = 'flex';
    });
  }

  // 7. Live Chat System Logic
  const chatBubbleBtn = document.getElementById('chat-bubble-btn');
  const chatWindow = document.getElementById('chat-window');
  const chatCloseBtn = document.getElementById('chat-close-btn');
  const chatIconOpen = document.getElementById('chat-icon-open');
  const chatIconClose = document.getElementById('chat-icon-close');
  const chatStatusIndicator = document.getElementById('chat-status-indicator');
  const chatHeaderStatusDot = document.getElementById('chat-header-status-dot');
  const chatHeaderStatusText = document.getElementById('chat-header-status-text');
  const chatStatusBanner = document.getElementById('chat-status-banner');
  const chatStatusBannerText = document.getElementById('chat-status-banner-text');
  const chatDynamicWelcome = document.getElementById('chat-dynamic-welcome');
  const adminStatusToggle = document.getElementById('admin-status-toggle');
  const chatInputForm = document.getElementById('chat-input-form');
  const chatTextInput = document.getElementById('chat-text-input');
  const chatMessagesBody = document.getElementById('chat-messages-body');
  const chatVisitorName = document.getElementById('chat-visitor-name');
  const chatVisitorEmail = document.getElementById('chat-visitor-email');

  let chatSessionId = localStorage.getItem('portfolio_chat_session_id') || ('sess_' + Math.random().toString(36).substring(2, 15));
  localStorage.setItem('portfolio_chat_session_id', chatSessionId);

  let isChatOpen = false;
  let chatPollInterval = null;
  let lastMessageCount = 0;

  function updateChatStatusUI(isOnline) {
    if (isOnline) {
      chatStatusIndicator.className = 'chat-status-indicator online';
      chatStatusIndicator.title = 'Status: Hasnain is Active';
      if (chatHeaderStatusDot) chatHeaderStatusDot.className = 'chat-header-status-dot online';
      if (chatHeaderStatusText) chatHeaderStatusText.innerHTML = '<span class="status-text-pill online">Active Now</span>';
      if (chatStatusBanner) {
        chatStatusBanner.className = 'chat-status-banner online';
        chatStatusBannerText.textContent = 'Hasnain is currently online & ready to chat.';
      }
      if (chatDynamicWelcome) {
        chatDynamicWelcome.textContent = "Hello! 👋 Thanks for visiting my portfolio. I'm currently active—feel free to send me a direct message about web development, Laravel, or collaboration!";
      }
    } else {
      chatStatusIndicator.className = 'chat-status-indicator offline';
      chatStatusIndicator.title = 'Status: Hasnain is Away';
      if (chatHeaderStatusDot) chatHeaderStatusDot.className = 'chat-header-status-dot offline';
      if (chatHeaderStatusText) chatHeaderStatusText.innerHTML = '<span class="status-text-pill offline">Away &bull; Leave a message</span>';
      if (chatStatusBanner) {
        chatStatusBanner.className = 'chat-status-banner offline';
        chatStatusBannerText.textContent = 'Hasnain is currently away from his desk. Leave a message.';
      }
      if (chatDynamicWelcome) {
        chatDynamicWelcome.textContent = "Hello! 👋 I'm currently away from my desk. Leave your message and contact info, and I'll receive it immediately!";
      }
    }
  }

  function fetchChatStatus() {
    fetch('/api/chat/status')
      .then(res => res.json())
      .then(data => {
        updateChatStatusUI(data.is_online);
      })
      .catch(err => console.debug('Chat status fetch notice:', err));
  }

  function fetchMessages() {
    fetch(`/api/chat/messages?session_id=${encodeURIComponent(chatSessionId)}`)
      .then(res => res.json())
      .then(data => {
        updateChatStatusUI(data.is_online);

        if (data.messages && data.messages.length > lastMessageCount) {
          // Clear previous dynamically rendered messages (keep welcome)
          const rows = chatMessagesBody.querySelectorAll('.chat-message-row.dynamic');
          rows.forEach(r => r.remove());

          data.messages.forEach(msg => {
            appendMessageToUI(msg.sender, msg.message, msg.time, false);
          });

          lastMessageCount = data.messages.length;
          chatMessagesBody.scrollTop = chatMessagesBody.scrollHeight;
        }
      })
      .catch(err => console.debug('Messages fetch notice:', err));
  }

  function appendMessageToUI(sender, text, time, shouldScroll = true) {
    const row = document.createElement('div');
    row.className = `chat-message-row ${sender} dynamic`;

    if (sender === 'admin') {
      row.innerHTML = `
        <div class="chat-msg-avatar">
          <img src="/images/profile.jpg" alt="Hasnain Al Ifan">
        </div>
        <div class="chat-bubble admin">
          <p>${escapeHtml(text)}</p>
          <span class="chat-msg-time">${time || 'Just now'}</span>
        </div>
      `;
    } else {
      row.innerHTML = `
        <div class="chat-bubble visitor">
          <p>${escapeHtml(text)}</p>
          <span class="chat-msg-time">${time || 'Just now'}</span>
        </div>
      `;
    }

    chatMessagesBody.appendChild(row);
    if (shouldScroll) {
      chatMessagesBody.scrollTop = chatMessagesBody.scrollHeight;
    }
  }

  function escapeHtml(string) {
    const div = document.createElement('div');
    div.textContent = string;
    return div.innerHTML;
  }

  function toggleChatWindow() {
    isChatOpen = !isChatOpen;
    chatWindow.style.display = isChatOpen ? 'flex' : 'none';
    chatBubbleBtn.setAttribute('aria-expanded', isChatOpen ? 'true' : 'false');

    if (isChatOpen) {
      chatIconOpen.style.display = 'none';
      chatIconClose.style.display = 'block';
      fetchMessages();
      chatTextInput.focus();
      chatPollInterval = setInterval(fetchMessages, 3500);
    } else {
      chatIconOpen.style.display = 'block';
      chatIconClose.style.display = 'none';
      if (chatPollInterval) clearInterval(chatPollInterval);
    }
  }

  if (chatBubbleBtn && chatWindow) {
    chatBubbleBtn.addEventListener('click', toggleChatWindow);
    if (chatCloseBtn) chatCloseBtn.addEventListener('click', toggleChatWindow);

    // Initial status fetch on page load
    fetchChatStatus();
    setInterval(fetchChatStatus, 15000); // Check status periodically
  }

  // Admin Quick Switcher
  if (adminStatusToggle) {
    adminStatusToggle.addEventListener('click', function (e) {
      e.stopPropagation();
      fetch('/api/chat/status', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
        },
      })
        .then(res => res.json())
        .then(data => {
          updateChatStatusUI(data.is_online);
        })
        .catch(err => console.error('Status toggle error:', err));
    });
  }

  // Handle message send
  if (chatInputForm) {
    chatInputForm.addEventListener('submit', function (e) {
      e.preventDefault();
      const text = chatTextInput.value.trim();
      if (!text) return;

      const vName = chatVisitorName ? chatVisitorName.value.trim() : '';
      const vEmail = chatVisitorEmail ? chatVisitorEmail.value.trim() : '';

      // Optimistic UI update
      appendMessageToUI('visitor', text, 'Just now', true);
      chatTextInput.value = '';
      lastMessageCount++;

      fetch('/api/chat/send', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
        },
        body: JSON.stringify({
          session_id: chatSessionId,
          message: text,
          visitor_name: vName,
          visitor_email: vEmail,
        }),
      })
        .then(res => res.json())
        .then(data => {
          console.debug('Chat message sent:', data);
        })
        .catch(err => {
          console.error('Error sending chat message:', err);
        });
    });
  }
});

